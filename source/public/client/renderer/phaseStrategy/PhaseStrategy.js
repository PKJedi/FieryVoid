'use strict';

window.PhaseStrategy = function () {

    function PhaseStrategy(coordinateConverter) {
        this.inactive = true;
        this.gamedata = null;
        this.shipIconContainer = null;
        this.ewIconContainer = null;
        this.ballisticIconContainer = null;
        this.coordinateConverter = coordinateConverter;
        this.currentlyMouseOveredIds = null;

        this.onMouseOutCallbacks = [];
        this.onZoomCallbacks = [this.repositionTooltip.bind(this), this.positionMovementUI.bind(this), this.repositionSelectFromShips.bind(this)];
        this.onScrollCallbacks = [this.repositionTooltip.bind(this), this.positionMovementUI.bind(this), this.repositionSelectFromShips.bind(this)];
        this.onClickCallbacks = [];

        this.selectedShip = null;
        this.targetedShip = null;
        this.animationStrategy = null;
        this.replayUI = null;

        this.shipTooltip = null;
        this.selectFromShips = null;
        this.movementUI = null;

        this.onDoneCallback = null;
        this.uiManager = new window.UIManager($("body")[0]);
    }

    PhaseStrategy.prototype.consumeGamedata = function () {
        this.shipIconContainer.consumeGamedata(this.gamedata);
        this.animationStrategy.update(this.gamedata);
        this.ewIconContainer.consumeGamedata(this.gamedata, this.shipIconContainer);
        this.ballisticIconContainer.consumeGamedata(this.gamedata, this.shipIconContainer);
        this.redrawMovementUI();
    };

    PhaseStrategy.prototype.render = function (coordinateConverter, scene, zoom) {
        this.animationStrategy.render(coordinateConverter, scene, zoom);
    };

    PhaseStrategy.prototype.update = function (gamedata) {
        this.gamedata = gamedata;
        this.consumeGamedata();
        this.ewIconContainer.hide();
        this.ballisticIconContainer.show();
    };

    PhaseStrategy.prototype.activate = function (shipIcons, ewIconContainer, ballisticIconContainer, gamedata, webglScene, doneCallback) {
        this.shipIconContainer = shipIcons;
        this.ewIconContainer = ewIconContainer;
        this.ballisticIconContainer = ballisticIconContainer;
        this.gamedata = gamedata;
        this.inactive = false;
        this.consumeGamedata();
        this.shipIconContainer.setAllSelected(false);
        this.ballisticIconContainer.show();
        this.onDoneCallback = doneCallback;
        this.createReplayUI(gamedata);
        return this;
    };

    PhaseStrategy.prototype.deactivate = function () {
        this.inactive = true;
        this.animationStrategy.deactivate();
        this.replayUI && this.replayUI.deactivate();

        if (this.ballisticIconContainer) {
            this.ballisticIconContainer.hide();
        }

        if (this.ewIconContainer) {
            this.ewIconContainer.hide();
        }

        if (this.shipTooltip) {
            this.shipTooltip.destroy();
        }

        if (this.selectedShip) {
            this.deselectShip(this.selectedShip);
        }

        this.currentlyMouseOveredIds = null;

        return this;
    };

    PhaseStrategy.prototype.onEvent = function (name, payload) {
        var target = this['on' + name];
        if (target && typeof target == 'function') {
            target.call(this, payload);
        }
    };

    PhaseStrategy.prototype.onScrollToShip = function(payload) {
        var icon = this.shipIconContainer.getById(payload.shipId)
        window.webglScene.moveCameraTo(icon.getPosition())
    }

    PhaseStrategy.prototype.onScrollEvent = function (payload) {
        this.onScrollCallbacks = this.onScrollCallbacks.filter(function (callback) {
            return callback(payload);
        });
    };

    PhaseStrategy.prototype.onZoomEvent = function (payload) {
        this.onZoomCallbacks = this.onZoomCallbacks.filter(function (callback) {
            return callback(payload);
        });
    };

    PhaseStrategy.prototype.onClickEvent = function (payload) {
        var icons = getInterestingStuffInPosition.call(this, payload, this.gamedata.turn);

        this.onClickCallbacks = this.onClickCallbacks.filter(function (callback) {
            callback();
            return false;
        });

        if (icons.length > 1) {
            this.onShipsClicked(icons.map(function (icon) {
                return this.gamedata.getShip(icon.shipId);
            }, this), payload);
        } else if (icons.length === 1) {
            if (payload.button !== 0 && payload.button !== undefined) {
                this.onShipRightClicked(this.gamedata.getShip(icons[0].shipId), payload);
            } else {
                this.onShipClicked(this.gamedata.getShip(icons[0].shipId), payload);
            }
        } else {
            this.onHexClicked(payload);
        }
    };

    PhaseStrategy.prototype.onHexClicked = function (payload) {};

    PhaseStrategy.prototype.onShipsClicked = function (ships, payload) {
        this.showSelectFromShips(ships, payload)
    };

    PhaseStrategy.prototype.onShipRightClicked = function (ship) {
        if (this.gamedata.isMyShip(ship)) {
            this.setSelectedShip(ship);
        }
        shipWindowManager.open(ship);
    };

    PhaseStrategy.prototype.onShipClicked = function (ship, payload) {
        if (this.gamedata.isMyShip(ship)) {
            this.selectShip(ship, payload);
        } else {
            this.targetShip(ship, payload);
        }
    };

    PhaseStrategy.prototype.selectShip = function (ship, payload) {
        this.setSelectedShip(ship);
        var menu = new ShipTooltipMenu(this.selectedShip, ship, this.gamedata.turn);
        this.showShipTooltip(ship, payload, menu, false);
    };

    PhaseStrategy.prototype.setSelectedShip = function (ship) {
        if (this.selectedShip) {
            this.deselectShip(this.selectedShip);
        }

        this.selectedShip = ship;
        this.shipIconContainer.getByShip(ship).setSelected(true);
        this.shipIconContainer.getByShip(ship).showEW();
        
        if (this.shipTooltip) {
            this.shipTooltip.update(ship, this.selectedShip);
        }
    };

    PhaseStrategy.prototype.deselectShip = function (ship) {
        this.shipIconContainer.getById(ship.id).setSelected(false);

        gamedata.selectedSystems.slice(0).forEach(function (selected) {
            weaponManager.unSelectWeapon(this.selectedShip, selected);
        }, this);

        this.selectedShip = null;
    };

    PhaseStrategy.prototype.targetShip = function (ship, payload) {
        var menu = new ShipTooltipMenu(this.selectedShip, ship, this.gamedata.turn);
        this.showShipTooltip(ship, payload, menu, false);
    };

    /*
    PhaseStrategy.prototype.targetShip = function (ship) {
        if (this.targetedShip) {
            this.untargetShip(this.targetedShip);
        }
        this.targetedShip = ship;
        this.shipIconContainer.getById(ship.id).setSelected(true);
    };

    PhaseStrategy.prototype.untargetShip = function (ship) {
        this.shipIconContainer.getById(ship.id).setSelected(false);
        this.targetedShip = null;
    };
    */

    PhaseStrategy.prototype.onMouseMoveEvent = function (payload) {
        var icons = getInterestingStuffInPosition.call(this, payload, this.gamedata.turn);

        function doMouseOut() {
            if (this.currentlyMouseOveredIds) {
                this.currentlyMouseOveredIds = null;
            }

            this.onMouseOutCallbacks = this.onMouseOutCallbacks.filter(function (callback) {
                callback();
                return false;
            });

            this.onMouseOutShips(gamedata.ships, payload);
        }

        if (icons.length === 0 && this.currentlyMouseOveredIds !== null) {
            doMouseOut.call(this);
            return;
        } else if (icons.length === 0) {
            return;
        }

        var mouseOverIds = icons.reduce(function (value, icon) {
            return value + icon.shipId;
        }, '');

        if (mouseOverIds === this.currentlyMouseOveredIds) {
            return;
        }

        doMouseOut.call(this);

        this.currentlyMouseOveredIds = mouseOverIds;

        var ships = icons.map(function (icon) {
            return this.gamedata.getShip(icon.shipId);
        }, this);
        if (ships.length > 1) {
            this.onMouseOverShips(ships, payload);
        } else {
            this.onMouseOverShip(ships[0], payload);
        }
    };

    PhaseStrategy.prototype.onMouseOutShips = function (ships, payload) {
        ships = [].concat(ships);
        ships.forEach(function (ship) {
            var icon = this.shipIconContainer.getById(ship.id);
            icon.hideEW();
            icon.hideBDEW();
            this.ewIconContainer.hide();
            //TODO: User settings, should this be hidden or not?
            icon.showSideSprite(false);
        }, this);
    };

    PhaseStrategy.prototype.onMouseOverShips = function (ships, payload) {
        if (this.shipTooltip && this.shipTooltip.isForAnyOf(ships)) {
            return;
        }
        this.showShipTooltip(ships, payload, null, true);
    };

    PhaseStrategy.prototype.onMouseOverShip = function (ship, payload) {
        var icon = this.shipIconContainer.getById(ship.id);
        this.showShipTooltip(ship, payload, null, true);
        this.showShipEW(ship);
        icon.showSideSprite(true);
        icon.showBDEW();
    };

    PhaseStrategy.prototype.showShipEW = function (ship) {
        this.shipIconContainer.getByShip(ship).showEW();
        this.ewIconContainer.showForShip(ship);
    };

    PhaseStrategy.prototype.hideShipEW = function (ship) {
        this.shipIconContainer.getByShip(ship).hideEW();
        this.ewIconContainer.hide();
    };

    PhaseStrategy.prototype.showShipTooltip = function (ships, payload, menu, hide, ballisticsMenu) {

        if (this.shipTooltip) {
            this.hideShipTooltip(this.shipTooltip)
        }

        ships = [].concat(ships);

        var position = payload.hex;
        if (ships.length === 1) {
            position = this.shipIconContainer.getByShip(ships[0]).getPosition();
        }

        if (!ballisticsMenu) {
            ballisticsMenu = new ShipTooltipBallisticsMenu(this.shipIconContainer, this.gamedata.turn, false);
        }

        var shipTooltip = new window.ShipTooltip(this.selectedShip, ships, position, shipManager.systems.selectedShipHasSelectedWeapons(this.selectedShip), menu, payload.hex, ballisticsMenu);

        this.shipTooltip = shipTooltip;
        this.onClickCallbacks.push(this.hideShipTooltip.bind(this, shipTooltip));

        if (hide) {
            this.onMouseOutCallbacks.push(this.hideShipTooltip.bind(this, shipTooltip));
        }
    };

    PhaseStrategy.prototype.showSelectFromShips = function (ships, payload) {
        var selectFromShips = new window.SelectFromShips(this.selectedShip, ships, payload, this)
        this.selectFromShips = selectFromShips;
        this.onClickCallbacks.push(this.hideSelectFromShips.bind(this, selectFromShips));
    };

    PhaseStrategy.prototype.hideShipTooltip = function (shipTooltip) {
        if (this.shipTooltip && this.shipTooltip === shipTooltip) {
            this.shipTooltip.destroy();
            this.shipTooltip = null;
        }
    };

    PhaseStrategy.prototype.hideSelectFromShips = function (selectFromShips) {
        if (this.selectFromShips && this.selectFromShips === selectFromShips) {
            this.selectFromShips.destroy();
            this.selectFromShips = null;
        }
    };

    PhaseStrategy.prototype.repositionSelectFromShips = function () {
        if (this.selectFromShips) {
            this.selectFromShips.reposition();
        }

        return true;
    };


    PhaseStrategy.prototype.repositionTooltip = function () {
        if (this.shipTooltip) {
            this.shipTooltip.reposition();
        }

        return true;
    };

    PhaseStrategy.prototype.positionMovementUI = function () {
        if (!this.movementUI) {
            return true;
        }

        var pos = this.coordinateConverter.fromGameToViewPort(this.movementUI.icon.getPosition());
        var heading = mathlib.hexFacingToAngle(this.movementUI.icon.getLastMovement().heading);

        UI.shipMovement.reposition(pos, heading);

        return true;
    };

    PhaseStrategy.prototype.redrawMovementUI = function () {

        if (!this.selectedShip) {
            return;
        }

        if (this.movementUI && this.movementUI.ship.movement.some(function (movement) {
            return !movement.commit;
        })) {
            this.hideMovementUI();
            return;
        }

        this.drawMovementUI(this.selectedShip);
    };

    PhaseStrategy.prototype.drawMovementUI = function (ship) {
        UI.shipMovement.drawShipMovementUI(ship, new ShipMovementCallbacks(ship, this.onShipMovementChanged.bind(this)));
        this.movementUI = {
            element: UI.shipMovement.uiElement,
            ship: ship,
            icon: this.shipIconContainer.getByShip(ship),
            position: null
        };

        UI.shipMovement.show();
        this.positionMovementUI();
    };

    PhaseStrategy.prototype.hideMovementUI = function () {
        UI.shipMovement.hide();
        this.movementUI = null;
    };

    PhaseStrategy.prototype.selectFirstOwnShipOrActiveShip = function () {
        var ship = gamedata.getFirstFriendlyShip();
        //TODO: what about active ship?
        if (ship) {
            this.setSelectedShip(ship);
        }
    };

    PhaseStrategy.prototype.selectActiveShip = function () {
        var ship = gamedata.getActiveShip();

        if (ship && gamedata.isMyShip(ship)) {
            this.setSelectedShip(ship);
        }
    };

    PhaseStrategy.prototype.done = function () {
        if (this.onDoneCallback) {
            this.onDoneCallback();
        }
    };

    PhaseStrategy.prototype.onWeaponMouseOver = function (payload) {
        var ship = payload.ship;
        var weapon = payload.weapon;
        var element = payload.element;
        systemInfo.showSystemInfo(element, weapon, ship, this.selectedShip);

        this.shipIconContainer.getArray().forEach(function (icon) {
            icon.hideWeaponArcs();
        });
        var icon = this.shipIconContainer.getByShip(ship);
        icon.showWeaponArc(ship, weapon);
    };

    PhaseStrategy.prototype.onWeaponMouseOut = function () {
        this.shipIconContainer.getArray().forEach(function (icon) {
            icon.hideWeaponArcs();
        });
    };

    PhaseStrategy.prototype.createReplayUI = function (gamedata) {
        this.replayUI = new ReplayUI().activate();
    };

    PhaseStrategy.prototype.changeAnimationStrategy = function (newAnimationStartegy) {
        this.animationStrategy && this.animationStrategy.deactivate();
        this.animationStrategy = newAnimationStartegy;
        this.animationStrategy.activate();
    };

    function getInterestingStuffInPosition(payload, turn) {
        return this.shipIconContainer.getIconsInProximity(payload).filter(function (icon) {
            var turnDestroyed = shipManager.getTurnDestroyed(icon.ship);
            return turnDestroyed === null || turnDestroyed >= turn;
        });
    }

    PhaseStrategy.prototype.setPhaseHeader = function (name, shipName) {

        if (name === false) {
            jQuery("#phaseheader").hide();
            return;
        }

        if (!shipName) {
            shipName = "";
        }

        $("#phaseheader .turn.value").html("TURN: " + this.gamedata.turn + ",");
        $("#phaseheader .phase.value").html(name);
        $("#phaseheader .activeship.value").html(shipName);
        $("#phaseheader").show();
    };

    PhaseStrategy.prototype.onShipEwChanged = function (payload) {
        var ship = payload.ship;

        if (this.shipTooltip) {
            this.shipTooltip.update(ship, this.selectedShip);
        }

        this.shipIconContainer.getByShip(ship).consumeEW(ship);
        this.ewIconContainer.updateForShip(ship);
        window.shipWindowManager.addEW(ship)
    };

    PhaseStrategy.prototype.onShipMovementChanged = function (payload) {
        var ship = payload.ship;
        this.shipIconContainer.getByShip(ship).consumeMovement(ship.movement);
        if (this.animationStrategy) {
            this.animationStrategy.shipMovementChanged(ship);
        }
        this.redrawMovementUI(ship);
    };

    PhaseStrategy.prototype.onShowAllEW = function (payload) {
        showGlobalEW.call(this, gamedata.ships, payload);
    };

    PhaseStrategy.prototype.onShowFriendlyEW = function (payload) {
        showGlobalEW.call(this, gamedata.ships.filter(function(ship){ return isMyOrTeamOneShip(ship) }), payload);
    };

    PhaseStrategy.prototype.onShowEnemyEW = function (payload) {
        showGlobalEW.call(this, gamedata.ships.filter(function(ship){ return !isMyOrTeamOneShip(ship) }), payload);
    };

    function showGlobalEW(ships, payload) {
        if (payload.up) {
            ships.forEach(function (ship) {
                var icon = this.shipIconContainer.getById(ship.id);
                icon.hideEW();
                icon.hideBDEW();
                this.ewIconContainer.hide();
            }, this);
        } else {
            ships.forEach(function (ship) {
                var icon = this.shipIconContainer.getById(ship.id);
                this.ewIconContainer.showByShip(ship);
                icon.showEW();
                icon.showBDEW();
            }, this);
        }
    }

    function isMyOrTeamOneShip(ship) {
        if (gamedata.thisplayer) {
            return gamedata.isMyShip(ship);
        } else {
            return ship.team === 1;
        }
    }


    return PhaseStrategy;
}();