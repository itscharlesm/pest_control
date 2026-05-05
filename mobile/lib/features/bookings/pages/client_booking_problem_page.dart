import 'package:flutter/material.dart';
import 'package:mobile_app/app/theme.dart';
import 'package:mobile_app/shared/widgets/headers/app_back_header.dart';
import 'package:mobile_app/features/bookings/widgets/booking_step_indicator.dart';
import 'package:mobile_app/features/bookings/pages/client_booking_schedule_page.dart';

class ClientBookingProblemPage extends StatefulWidget {
  final String email;

  const ClientBookingProblemPage({
    super.key,
    required this.email,
  });

  @override
  State<ClientBookingProblemPage> createState() =>
      _ClientBookingProblemPageState();
}

class _ClientBookingProblemPageState
    extends State<ClientBookingProblemPage> {
  String? selectedPest;
  List<String> selectedAreas = [];

  final List<String> pests = [
    'Cockroaches',
    'Ants',
    'Rats/Mice',
    'Termites',
    'Mosquitoes',
    'Bed Bugs',
    'Flies',
    'Spiders',
    'Other'
  ];

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
          const SizedBox(height: 16),
          const Text('Type of Pest',
              style: TextStyle(fontWeight: FontWeight.bold)),
          const SizedBox(height: 12),

          GridView.builder(
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            itemCount: pests.length,
            gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
              crossAxisCount: 3,
              crossAxisSpacing: 10,
              mainAxisSpacing: 10,
            ),
            itemBuilder: (context, index) {
              final pest = pests[index];
              final isSelected = selectedPest == pest;

              return GestureDetector(
                onTap: () {
                  setState(() {
                    selectedPest = pest;
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
                      const Icon(Icons.bug_report,
                          color: AppTheme.primaryRed),
                      const SizedBox(height: 6),
                      Text(pest,
                          textAlign: TextAlign.center,
                          style: const TextStyle(fontSize: 12)),
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
        onPressed: (selectedPest != null && selectedAreas.isNotEmpty)
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
}