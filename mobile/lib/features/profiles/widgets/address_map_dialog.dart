import 'package:flutter/material.dart';
import 'package:geolocator/geolocator.dart' as geo;
import 'package:mapbox_maps_flutter/mapbox_maps_flutter.dart';
import 'package:mobile_app/app/theme.dart';

class AddressMapDialog extends StatefulWidget {
  const AddressMapDialog({super.key});

  @override
  State<AddressMapDialog> createState() => _AddressMapDialogState();
}

class _AddressMapDialogState extends State<AddressMapDialog> {
  geo.Position? currentPosition;
  bool isLoadingLocation = true;

  MapboxMap? mapboxMap;

  double? selectedLatitude;
  double? selectedLongitude;

  @override
  void initState() {
    super.initState();
    _loadCurrentLocation();
  }

  Future<void> _loadCurrentLocation() async {
    try {
      final position = await geo.Geolocator.getCurrentPosition(
        desiredAccuracy: geo.LocationAccuracy.medium,
        timeLimit: const Duration(seconds: 8),
      );

      if (!mounted) return;

      setState(() {
        currentPosition = position;
        isLoadingLocation = false;
      });
    } catch (e) {
      if (!mounted) return;

      setState(() {
        isLoadingLocation = false;
      });
    }
  }

  Future<void> _updatePinCoordinates() async {
    if (mapboxMap == null) return;

    final cameraState = await mapboxMap!.getCameraState();
    final coordinates = cameraState.center.coordinates;

    if (!mounted) return;

    setState(() {
      selectedLongitude = coordinates.lng.toDouble();
      selectedLatitude = coordinates.lat.toDouble();
    });
  }

  @override
  Widget build(BuildContext context) {
    final longitude = currentPosition?.longitude ?? 125.6131;
    final latitude = currentPosition?.latitude ?? 7.0731;

    return Dialog(
      backgroundColor: AppTheme.white,
      insetPadding: const EdgeInsets.symmetric(
        horizontal: 10,
        vertical: 10,
      ),
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(18),
      ),
      child: SizedBox(
        width: double.infinity,
        height: MediaQuery.of(context).size.height * 0.82,
        child: Column(
          children: [
            Padding(
              padding: const EdgeInsets.fromLTRB(16, 14, 10, 10),
              child: Row(
                children: [
                  const Expanded(
                    child: Text(
                      'Pin Your Address',
                      style: TextStyle(
                        color: AppTheme.black,
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
                  IconButton(
                    onPressed: () => Navigator.pop(context),
                    icon: const Icon(Icons.close_rounded),
                  ),
                ],
              ),
            ),

            const Divider(height: 1),

            Expanded(
              child: ClipRRect(
                borderRadius: const BorderRadius.vertical(
                  bottom: Radius.circular(18),
                ),
                child: isLoadingLocation
                    ? const Center(
                        child: CircularProgressIndicator(),
                      )
                    : Stack(
                        children: [
                          MapWidget(
                            cameraOptions: CameraOptions(
                              center: Point(
                                coordinates: Position(
                                  longitude,
                                  latitude,
                                ),
                              ),
                              zoom: 15,
                            ),
                            onMapCreated: (controller) async {
                              mapboxMap = controller;

                              controller.scaleBar.updateSettings(
                                ScaleBarSettings(
                                  enabled: false,
                                ),
                              );

                              await controller.location.updateSettings(
                                LocationComponentSettings(
                                  enabled: true,
                                  pulsingEnabled: true,
                                ),
                              );

                              await Future.delayed(
                                const Duration(milliseconds: 500),
                              );

                              await _updatePinCoordinates();
                            },
                            onCameraChangeListener: (eventData) async {
                              await _updatePinCoordinates();
                            },
                          ),

                          IgnorePointer(
                            child: Center(
                              child: Transform.translate(
                                offset: const Offset(0, -23),
                                child: Image.asset(
                                  'assets/images/img_map_pin.png',
                                  width: 46,
                                  height: 46,
                                ),
                              ),
                            ),
                          ),
                        ],
                      ),
              ),
            ),

            Container(
              padding: const EdgeInsets.fromLTRB(16, 12, 16, 16),
              decoration: const BoxDecoration(
                color: AppTheme.white,
                borderRadius: BorderRadius.vertical(
                  bottom: Radius.circular(18),
                ),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    selectedLatitude == null
                        ? 'Move the map to place the pin on your exact address.'
                        : 'Selected Location:\nLatitude: $selectedLatitude\nLongitude: $selectedLongitude',
                    style: const TextStyle(
                      fontSize: 13,
                      color: AppTheme.black,
                      height: 1.4,
                    ),
                  ),

                  const SizedBox(height: 12),

                  SizedBox(
                    width: double.infinity,
                    height: 48,
                    child: ElevatedButton(
                      onPressed: selectedLatitude == null ||
                              selectedLongitude == null
                          ? null
                          : () {
                              Navigator.pop(context, {
                                'latitude': selectedLatitude,
                                'longitude': selectedLongitude,
                              });
                            },
                      child: const Text('Use This Location'),
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}