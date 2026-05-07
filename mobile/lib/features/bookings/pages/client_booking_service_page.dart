import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:mobile_app/config/api_config.dart';
import 'package:flutter/material.dart';
import 'package:mobile_app/app/theme.dart';
import 'package:mobile_app/shared/widgets/headers/app_back_header.dart';
import 'package:mobile_app/features/bookings/widgets/booking_step_indicator.dart';
import 'package:mobile_app/features/bookings/pages/client_booking_schedule_page.dart';

  class ClientBookingProblemPage extends StatefulWidget {
    final String email;
    final Map<String, dynamic> selectedAddress;

    const ClientBookingProblemPage({
      super.key,
      required this.email,
      required this.selectedAddress,
    });

    @override
    State<ClientBookingProblemPage> createState() =>
        _ClientBookingProblemPageState();
  }

  class _ClientBookingProblemPageState extends State<ClientBookingProblemPage> {
    Map<String, dynamic>? selectedServicePackage;
    List<String> selectedAreas = [];

    List<Map<String, dynamic>> servicePackages = [];
    bool isLoadingPackages = false;

    final Map<String, int> areas = {
      'Bedroom': 10,
      'Living Room': 30,
      'Kitchen': 25,
      'Bathroom': 15,
      'Dining Room': 20,
      'Garage': 20,
      'Garden/Yard': 35,
      'Basement': 25,
    };

    final TextEditingController descriptionController =
        TextEditingController();

    int get totalPrice {
      int total = 0;
      for (var area in selectedAreas) {
        total += areas[area]!;
      }
      return total;
    }

    String _formatServiceName(String text) {
      return text
          .toLowerCase()
          .split(' ')
          .map((word) {
            if (word.isEmpty) return word;

            return word[0].toUpperCase() + word.substring(1);
          })
          .join(' ');
    }

    @override
    void initState() {
      super.initState();
      _loadServicePackages();
    }

    @override
    Widget build(BuildContext context) {
      return Scaffold(
        backgroundColor: AppTheme.lightGray,
        appBar: const AppBackHeader(
          title: 'Book Service',
        ),
        body: Column(
          children: [
            BookingStepIndicator(currentStep: 2),
            Expanded(
              child: SingleChildScrollView(
                padding: const EdgeInsets.all(20),
                child: Column(
                  children: [
                    _problemCard(),
                    const SizedBox(height: 20),
                    _areasCard(),
                    const SizedBox(height: 20),
                    _descriptionCard(),
                  ],
                ),
              ),
            ),
            _bottomBar(),
          ],
        ),
      );
    }

    Widget _problemCard() {
      return Container(
        padding: const EdgeInsets.all(16),
        decoration: AppTheme.borderedCardDecoration,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              "What's the Problem?",
              style: TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
                color: AppTheme.black,
              ),
            ),

            const SizedBox(height: 4),

            const Text(
              'Tell us about your pest issue and affected areas.',
              style: TextStyle(
                color: AppTheme.gray,
                fontSize: 13,
                height: 1.3,
              ),
            ),

            const SizedBox(height: 16),
            const Text('Type of Pest', style: TextStyle(fontWeight: FontWeight.bold)),
            const SizedBox(height: 12),

            if (isLoadingPackages)
              const Center(
                child: Padding(
                  padding: EdgeInsets.all(20),
                  child: CircularProgressIndicator(
                    color: AppTheme.primaryRed,
                  ),
                ),
              )
            else if (servicePackages.isEmpty)
              const Text(
                'No service packages available.',
                style: TextStyle(
                  color: AppTheme.gray,
                  fontSize: 13,
                ),
              )
            else
              GridView.builder(
                shrinkWrap: true,
                physics: const NeverScrollableScrollPhysics(),
                itemCount: servicePackages.length,
                gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                  crossAxisCount: 3,
                  crossAxisSpacing: 10,
                  mainAxisSpacing: 10,
                ),
                itemBuilder: (context, index) {
                  final service = servicePackages[index];
                  final isSelected =
                      selectedServicePackage?['id'] == service['id'];

                  return GestureDetector(
                    onTap: () {
                      setState(() {
                        selectedServicePackage = service;
                      });
                    },
                    child: Container(
                      padding: const EdgeInsets.all(10),
                      decoration: BoxDecoration(
                        color: isSelected
                            ? AppTheme.primaryRed.withOpacity(0.1)
                            : AppTheme.white,
                        borderRadius: BorderRadius.circular(12),
                        border: Border.all(
                          color: isSelected
                              ? AppTheme.primaryRed
                              : AppTheme.borderGray,
                        ),
                      ),
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          const Icon(
                            Icons.bug_report,
                            color: AppTheme.primaryRed,
                          ),
                          const SizedBox(height: 6),
                          Text(
                            _formatServiceName(service['name'] ?? ''),
                            textAlign: TextAlign.center,
                            style: const TextStyle(fontSize: 12),
                          ),
                        ],
                      ),
                    ),
                  );
                },
              ),
          ],
        ),
      );
    }

    Widget _areasCard() {
      return Container(
        padding: const EdgeInsets.all(16),
        decoration: AppTheme.borderedCardDecoration,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text('Areas to Treat',
                style: TextStyle(fontWeight: FontWeight.bold)),
            const SizedBox(height: 12),

            GridView.builder(
              shrinkWrap: true,
              physics: const NeverScrollableScrollPhysics(),
              itemCount: areas.length,
              gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                crossAxisCount: 2,
                crossAxisSpacing: 10,
                mainAxisSpacing: 10,
                childAspectRatio: 1.8,
              ),
              itemBuilder: (context, index) {
                final entry = areas.entries.toList()[index];
                final isSelected = selectedAreas.contains(entry.key);

                return GestureDetector(
                  onTap: () {
                    setState(() {
                      if (isSelected) {
                        selectedAreas.remove(entry.key);
                      } else {
                        selectedAreas.add(entry.key);
                      }
                    });
                  },
                  child: Container(
                    padding: const EdgeInsets.all(12),
                    decoration: BoxDecoration(
                      color: isSelected
                          ? AppTheme.primaryRed.withOpacity(0.1)
                          : AppTheme.white,
                      borderRadius: BorderRadius.circular(12),
                      border: Border.all(
                        color: isSelected
                            ? AppTheme.primaryRed
                            : AppTheme.borderGray,
                      ),
                    ),
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Text(entry.key),
                        Text('₱${entry.value}',
                            style: const TextStyle(
                                color: AppTheme.primaryRed,
                                fontWeight: FontWeight.bold)),
                      ],
                    ),
                  ),
                );
              },
            ),

            const SizedBox(height: 10),
            Text('Estimated Total: ₱$totalPrice',
                style: const TextStyle(
                    color: AppTheme.primaryRed,
                    fontWeight: FontWeight.bold)),
          ],
        ),
      );
    }

    Widget _descriptionCard() {
      return Container(
        padding: const EdgeInsets.all(16),
        decoration: AppTheme.borderedCardDecoration,
        child: TextField(
          controller: descriptionController,
          maxLines: 4,
          decoration: const InputDecoration(
            labelText: 'Describe the problem',
          ),
        ),
      );
    }

    Widget _bottomBar() {
      return Container(
        padding: const EdgeInsets.all(16),
        color: AppTheme.white,
        child: ElevatedButton(
          onPressed: (selectedServicePackage != null && selectedAreas.isNotEmpty)
              ? () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (_) => ClientBookingSchedulePage(
                        email: widget.email,
                      ),
                    ),
                  );
                }
              : null,
          child: const Text('Continue'),
        ),
      );
    }

    Future<void> _loadServicePackages() async {
      setState(() {
        isLoadingPackages = true;
      });

      try {
        final response = await http.get(
          Uri.parse('${ApiConfig.baseUrl}/api/mobile/service-packages'),
          headers: {
            'Accept': 'application/json',
          },
        );

        final data = jsonDecode(response.body);

        if (data['success'] == true) {
          final List packageData = data['data'] ?? [];

          setState(() {
            servicePackages = packageData.map((item) {
              return {
                'id': item['svcp_id'],
                'name': item['svcp_pest_type'],
              };
            }).toList();

            servicePackages.sort((a, b) {
              if (a['name'] == 'OTHERS') return 1;
              if (b['name'] == 'OTHERS') return -1;
              return 0;
            });
          });
        }
      } catch (e) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Unable to load service packages.'),
          ),
        );
      } finally {
        if (mounted) {
          setState(() {
            isLoadingPackages = false;
          });
        }
      }
    }
  }