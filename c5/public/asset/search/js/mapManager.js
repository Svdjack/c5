/* global DG */

var mapManager = (function () {
    /* magic variable */
    const MOVE_OFFSET_X = -460;
    
    let moveMapInAnimate = false;
    let cbMapCreate = [];
    let cbMapCreateOnlyOne = false;
    
    var map;
    var yandexMap;
    var myPosition;
    var markerDrawer;
    var markersData = [];
    var comments = [];
    var locationPreset = '/asset/search/images/4.svg';
    var activePreset = '/asset/search/images/2.svg';
    var visitPreset = '/asset/search/images/3.svg';
    var defaultPreset = '/asset/search/images/1.svg';
    var visitedPreset = '/asset/search/images/5.svg';
    let firmActive = 0;
    let _setActiveMarker = function (firmId) {
        var index = mapManager.getIndex(firmId);
        try {
            markersData.forEach(function callback(element, i) {
                if (index == i) {
                    element.iconIndex = 2;
                } else {
                    //element.iconIndex = 0;
                }
            });
            markerDrawer.update();
        } catch (e) {
            console.log('Ошибка ' + e.message);
        }
    };
    let _showMarker = function (firmId) {
        var index = mapManager.getIndex(firmId);
        var marker = markersData[index];
        map.setView([marker.position[1], marker.position[0]], map.getZoom());
    };
    
    let _getCurrentPosition = function ()  {
        return map.getCenter();
    };
    
    let _setFirmActive = function(firm) {
        if (firm) {
            firmActive = firm;
            window.userInterface.saveActiveFirm(firm);
        }
    }
    
    let _cbSetMapCreate = function(cb) {        
        if(!markerDrawer) {
            cbMapCreate.push(cb);
        }
        else {
            cb();
        }
    };

    return {
        getMap: function() {
            return map;
        },
        cbSetMapCreate: _cbSetMapCreate,
        setFirmActive: _setFirmActive,
        getCurrentPosition: _getCurrentPosition,
        createMap: function () {
            cbMapCreateOnlyOne = false;
            $('.map__zoom-item.map__zoom-item--close').remove();

            map = DG.map('sm', {
                center: paramsManager.getMapCenter() ? paramsManager.getMapCenter() : [City.lat, City.lon],
                zoom: paramsManager.get('zoom'),
                fullscreenControl: false,
                zoomControl: false
            });            
            if (!isMob && !paramsManager.getMapCenter()) {
                map.fitBounds(map.getBounds(), {
                    paddingTopLeft: [500, 0]
                });
            }

            map.on('zoom', function () {
                //при перерисовке маркер не рисуется
                return;
                if (map.getZoom() > 15) {
                    mapManager.setPoints(0.6);
                }
                if (map.getZoom() == 14) {
                    mapManager.setPoints(null);
                }
            })

            map.on('zoomend', function () {
                paramsManager.set('center', [map.getCenter()['lat'], map.getCenter()['lng']]);
                paramsManager.setUrl('center');
                paramsManager.set('zoom', map.getZoom());
                paramsManager.setUrl('zoom');
            });
            map.on('moveend', function () {
                paramsManager.set('center', [map.getCenter()['lat'], map.getCenter()['lng']]);
                paramsManager.setUrl('center');
                paramsManager.set('zoom', map.getZoom());
                paramsManager.setUrl('zoom');
            });


            this.redrawPoints();
        },
        
        zoomPlus: function () {
            map.zoomIn();
            if (mapManager.isYandexMap()) {
                yandexMap.setZoom(yandexMap.getZoom() + 1, {duration: 200, checkZoomRange: true})
            }
        },

        zoomMinus: function () {
            map.zoomOut();
            if (mapManager.isYandexMap()) {
                yandexMap.setZoom(yandexMap.getZoom() - 1, {duration: 200, checkZoomRange: true})
            }
        },
        removeMyPosition: function () {
            if (myPosition) {
                myPosition && myPosition.remove();
            }
        },

        createWay: function (startPoint, endPoint) {
            $('.js-map__search-location').addClass('load');
            $('.map__zoom-item--close').remove();

            if (isMob) {
                $('.zoom-map').append('<span class="map__zoom-item map__zoom-item--close">&#x274C;</span>');
            } else {
                $('.map__search-location-input-wrap').append('<span class="map__zoom-item map__zoom-item--close">&#x274C;</span>');
            }

            $('.map__info-content').css('opacity', 0.5);
            if (!yandexMap) {
                map.remove();
                yandexMap = new ymaps.Map('sm', {
                    center: [City.lat, City.lon],
                    zoom: 12,
                    controls: [],
                    behaviors: ['default', 'scrollZoom']
                });
            } else {
                yandexMap.geoObjects.removeAll()
            }
            ymaps.route([
                startPoint,
                endPoint
            ], {mapStateAutoApply: true}).then(function (route) {
                yandexMap.geoObjects.add(route);
                var points = route.getWayPoints();
                var lastPoint = points.getLength() - 1;
                points.options.set('preset', 'islands#redCircleIcon');
                route.getPaths().options.set({strokeColor: '#ff0014'});
                points.get(0).properties.set('iconContent', 'А');
                points.get(lastPoint).properties.set('iconContent', 'Б');
                route.editor.start({addWayPoints: false, removeWayPoints: true, editWayPoints: true});

                points.events.add('dragend', function (event) {
                    var point = event.get('target');
                    var position = point.geometry.getCoordinates();
                    ymaps.geocode(position, {
                        results: 1
                    }).then(function (res) {
                        var firstGeoObject = res.geoObjects.get(0);
                        var address = City.name + ', ' + firstGeoObject.properties.get('name');
                        if (point.properties.get('iconContent') == 'А') {
                            $('#address').val(address);
                        } else {
                            $('#to').val(address);
                        }
                    });

                });
                $('.map__info-content').css('opacity', 1);
                $('.js-map__search-location').removeClass('load');
            }, function (error) {
                $('.js-map__search-location').removeClass('load');
                $('.map__info-content').css('opacity', 1);
                messageManager.show('Возникла ошибка: ' + error.message);
            });
            userInterface.cardHide();
        },

        setPoints: function (ratio) {
            if (mapManager.isYandexMap()) {
                yandexMap.destroy();
                yandexMap = false;
                mapManager.createMap();
                return true;
            }
            try {
                if (markerDrawer) {
                    markersData = [];
                    markerDrawer.remove();
                }

                for (var i = 0; i < FirmList.length; i++) {
                    markersData.push({
                        position: [FirmList[i].lon, FirmList[i].lat],
                        firmId: FirmList[i].id,
                    });
                }

                var pixelRatio = isMob ? 1 : 1.3;
                pixelRatio = ratio ? ratio : pixelRatio;
                var pin = new Image();
                var hoveredPin = new Image();
                var activePin = new Image();

                pin.src = defaultPreset;
                hoveredPin.src = activePreset;
                activePin.src = visitPreset;
                var atlas;
                if (window.Atlas && Atlas.Atlas) {
                    atlas = new Atlas.Atlas([{
                            image: pin,
                            anchor: [0.5, 1],
                            pixelDensity: pixelRatio,
                        }, {
                            image: hoveredPin,
                            anchor: [0.5, 1],
                            pixelDensity: pixelRatio,
                        }, {
                            image: activePin,
                            anchor: [0.5, 1],
                            pixelDensity: pixelRatio,
                            size: [40, 40],
                        }]);
                } else {
                    atlas = new MarkerDrawer.Atlas([{
                            image: pin,
                            anchor: [0.5, 1],
                            pixelDensity: pixelRatio,
                        }, {
                            image: hoveredPin,
                            anchor: [0.5, 1],
                            pixelDensity: pixelRatio,
                        }, {
                            image: activePin,
                            anchor: [0.5, 1],
                            pixelDensity: pixelRatio,
                            size: [40, 40],
                        }]);
                }

                markerDrawer = new MarkerDrawer.MarkerDrawer({
                    bufferFactor: 0.5,
                    zIndex: 99999,
                    onClickMarker: function() {
                        mapManager.setActiveMarkerLast();
                        if (jQuery(window).width() > 800) {
                            setTimeout(() => mapManager.moveMapTo(firmActive, null, 0), 200);
                        }
                    },
                    onClickAny: function () {
                        mapManager.setActiveMarkerLast();
                        userInterface.onclickMapAny();
                    }
                });
                (cbMapCreateOnlyOne === false) && cbMapCreate.map((currentValue) => {
                    currentValue();
                });
                cbMapCreateOnlyOne = true;
                markerDrawer.setAtlas(atlas);
                markerDrawer.setMarkers(markersData);

                markerDrawer.on('click', function (ev) {
                    firmActive = markersData[ev.marker].firmId;
                    ajaxManager.setFirm(firmActive);
                    $('.map__info-filter').hide();
                    $(filterButton).removeClass('active');
                    return false;
                });
                markerDrawer.on('mouseup', function (ev) {
                    markersData[ev.marker].iconIndex = 1;
                    markerDrawer.update();
                });
                markerDrawer.on('mouseover', function (ev) {
                    if (!markersData[ev.marker].iconIndex) {
                        markersData[ev.marker].iconIndex = 1;
                        markerDrawer.update();
                    }
                    map.getContainer().style['cursor'] = 'pointer';
                });
                markerDrawer.on('mouseout', function (ev) {
                    if (markersData[ev.marker].iconIndex === 1) {
                        markersData[ev.marker].iconIndex = 0;
                        markerDrawer.update();
                    }
                    map.getContainer().style['cursor'] = 'default';
                });
                markerDrawer.addTo(map);
                mapManager.drawMyPosition(false);

            } catch (e) {
                console.log('Ошибка ' + e.message);
            }
        },

        redrawPoints: function () {
            ajaxManager.getPoints(mapManager.setPoints);
        },

        flyTo: function (coords, zoom) {
            map.flyTo(coords, zoom, {duration: 0.3});
        },

        drawMyPosition: function (setCenter) {
            if (cookieManager.get('lat')) {
                try {
                    mapManager.removeMyPosition();
                    var point = [cookieManager.get('lat'), cookieManager.get('lon')];

                    var icon = DG.icon({
                        iconUrl: locationPreset,
                        iconSize: [30, 30]
                    });

                    myPosition = DG.marker(point, {
                        draggable: true,
                        icon: icon
                    }).addTo(map);

                    myPosition.on('dragend', function (e) {
                        var lat = e.target._latlng.lat.toFixed(20),
                            lng = e.target._latlng.lng.toFixed(20);
                        geocodeManager.setCoordinates([lat, lng]);
                        geocodeManager.setAddressByCoords([lat, lng]);
                        if (paramsManager.get('near')) {
                            ajaxManager.setFilters();
                        }
                    });

                    myPosition.addTo(map);
                    if (setCenter) {
                        map.setView(point, 14);
                    }
                } catch (e) {
                    console.log(e);
                }
            }
        },

        setActiveMarker: _setActiveMarker,
        
        setActiveMarkerLast: function () {
            _setActiveMarker(firmActive);
        },

        setDefaultMarker: function (firmId) {
            try {
                var index = mapManager.getIndex(firmId);
                markersData.forEach(function callback(element, i) {
                    if (index == i && firmId != paramsManager.get('card')) {
                        element.iconIndex = 0;
                    }
                });
                
                if (markerDrawer) {
                    markerDrawer.update();
                } else {
                    _cbSetMapCreate(() => {
                        markerDrawer.update();
                    });
                }
            } catch (e) {
                console.log('Ошибка ' + e.message);
            }
        },

        setVisitMarker: function (firmId) {
            
            var index = mapManager.getIndex(firmId);
            try {
                markersData.forEach(function callback(element, i) {
                    if (index == i) {
                        if (comments[firmId]) {
                            $('#comment').val(comments[firmId]);
                        }
                        element.iconIndex = 2;
                    } else {
                        element.iconIndex = 0;
                    }
                });
                markerDrawer.update();
            } catch (e) {
                console.log('Ошибка ' + e.message);
            }
        },
        showMarker: _showMarker,
        getIndex: function (firmId) {
            return markersData.findIndex(function (element) {
                if (element.firmId == firmId) {
                    return true;
                }
                return false;
            })
        },
        isYandexMap: function () {
            return yandexMap ? true : false;
        },
        moveMapTo: function (firmId, offsetX, offsetY) {
            
            if (moveMapInAnimate) {
                return;
            }
            
            var index = mapManager.getIndex(firmId);
            var marker = markersData[index];
            
            if (offsetX === null) {
                offsetX = MOVE_OFFSET_X;
            }

            if (!marker) {
                return false;
            }
            
            moveMapInAnimate = true;

            let X = Y = 0;
            X = marker.position[1];
            Y = marker.position[0];
            map.setView([X, Y], map.getZoom(), {
                animate: false
            });
            setTimeout(() => { 
                map.panBy(DG.point(offsetX || 0, offsetY || 0)) ;
                moveMapInAnimate = false;
            }, 200);
            return;
        },
        setFirmComment: function (text) {
            var firmId = paramsManager.get('card');
            var index = mapManager.getIndex(firmId);
            var marker = markersData[index];
            comments[firmId] = text;
            var icon = DG.icon({
                iconUrl: visitedPreset,
            });
            var label = DG.marker([marker.position[1], marker.position[0]], {icon: icon, opacity: 0,})
                .addTo(map);
            label.bindLabel(text, {static: true, className: 'gis-label'});
        },
        }
}());


var geocodeManager = (function () {
    var cookieLifetime = new Date(new Date().getTime() + 60 * 60 * 12 * 1000);
    var cookieOptions = new Object();
    cookieOptions.path = '/';
    cookieOptions.expires = cookieLifetime;
    return {
        setCoordinates: function (pos) {
            cookieManager.set('lat', pos[0], cookieOptions);
            cookieManager.set('lon', pos[1], cookieOptions);
        },

        getCoordinates: function () {
            if (cookieManager.get('lat'))
                return [cookieManager.get('lat'), cookieManager.get('lon')];
            return false;
        },

        getCurrentAddress: function () {
            return cookieManager.get('my_address');
        },

        setMyAddress: function (address) {
            geocodeManager.setCoordsByAddress(address);
            cookieManager.set('my_address', address);
        },

        getMyPosition: function (callback) {
            $('.js-map__search-location').addClass('load');
            ymaps.geolocation.get({
                provider: 'auto',
                mapStateAutoApply: true
            }).then(function (result) {
                var iam = result.geoObjects;
                geocodeManager.setCoordinates(iam.position);
                geocodeManager.setAddressByCoords(geocodeManager.getCoordinates());
                if (callback) {
                    callback();
                }
                $('.js-map__search-location').removeClass('load');
            });
        },

        setCoordsByAddress: function (address) {
            ymaps.geocode(address, {
                results: 1
            }).then(function (res) {
                var firstGeoObject = res.geoObjects.get(0),
                    pos = firstGeoObject.geometry.getCoordinates();
                cookieManager.set('lat', pos[0], cookieOptions);
                cookieManager.set('lon', pos[1], cookieOptions);
                mapManager.drawMyPosition(true);
            });
        },

        setAddressByCoords: function (pos) {
            ymaps.geocode(pos, {
                results: 1
            }).then(function (res) {
                var firstGeoObject = res.geoObjects.get(0);
                var localities = firstGeoObject.getLocalities();
                var address = City.name;
                if (localities.length > 0) {
                    address = localities[0];
                }
                address = address + ', ' + firstGeoObject.properties.get('name');
                cookieManager.set('my_address', address);
                userInterface.setMyAddress(address);
            });
        }
    }
}());
