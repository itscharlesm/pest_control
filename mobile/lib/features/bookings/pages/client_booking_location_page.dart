import 'package:flutter/material.dart';
import 'package:mobile_app/app/theme.dart';
import 'package:mobile_app/shared/widgets/headers/app_back_header.dart';
import 'package:mobile_app/features/bookings/pages/client_booking_problem_page.dart';
import 'package:mobile_app/features/bookings/widgets/booking_step_indicator.dart';
import 'package:mobile_app/shared/widgets/navigation/app_drawer.dart';
import 'package:mobile_app/shared/shared.dart';

class ClientBookingLocationPage extends StatefulWidget {
  final String email;

  const ClientBookingLocationPage({
    super.key,
    required this.email,
  });

  @override
  State<ClientBookingLocationPage> createState() =>
      _ClientBookingLocationPageState();
}

class _ClientBookingLocationPageState extends State<ClientBookingLocationPage> {
  String selectedLocationOption = 'saved';

  String? selectedSavedAddress;

  final TextEditingController manualAddressController =
      TextEditingController();

  final List<Map<String, String>> savedAddresses = [
    {
      'type': 'HOME',
      'address': 'Block 3 Lot 5, Sample Street, Davao City',
    },
    {
      'type': 'WORK',
      'address': 'Company Office, Bajada, Davao City',
    },
  ];

  @override
  void dispose() {
    manualAddressController.dispose();
    super.dispose();
  }

  void _continueToProblem() {
    if (selectedLocationOption == 'saved' && selectedSavedAddress == null) {
      _showMessage('Please select a saved address.');
      return;
    }

    if (selectedLocationOption == 'manual' &&
        manualAddressController.text.trim().isEmpty) {
      _showMessage('Please enter the service address.');
      return;
    }

    Navigator.push(
      context,
      MaterialPageRoute(
        builder: (_) => ClientBookingProblemPage(
          email: widget.email,
        ),
      ),
    );
  }

  void _showMessage(String message) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(message),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
    backgroundColor: AppTheme.lightGray,
    drawer: AppDrawer(
      userType: 3,
      email: widget.email,
      currentPage: 'book_service',
    ),
    appBar: const AppTitleHeader(
      title: 'Book Service',
    ),
    body: Column(
      children: [
        const BookingStepIndicator(currentStep: 1),

          Expanded(
            child: SingleChildScrollView(
              padding: const EdgeInsets.fromLTRB(20, 18, 20, 24),
              child: Column(
                children: [
                  _introCard(),
                  const SizedBox(height: 18),
                  _locationOptionCard(),
                  const SizedBox(height: 18),

                  if (selectedLocationOption == 'saved') _savedAddressCard(),
                  if (selectedLocationOption == 'manual') _manualAddressCard(),
                ],
              ),
            ),
          ),

          _bottomButton(),
        ],
      ),
    );
  }

  Widget _introCard() {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(16),
      decoration: AppTheme.borderedCardDecoration,
      child: const Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            'Where do you need the service?',
            style: TextStyle(
              color: AppTheme.black,
              fontSize: 18,
              fontWeight: FontWeight.bold,
            ),
          ),
          SizedBox(height: 8),
          Text(
            'Select one of your saved addresses or enter a new service location for this appointment.',
            style: TextStyle(
              color: AppTheme.gray,
              fontSize: 14,
              height: 1.4,
            ),
          ),
        ],
      ),
    );
  }

  Widget _locationOptionCard() {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(16),
      decoration: AppTheme.borderedCardDecoration,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            'Location Option',
            style: TextStyle(
              color: AppTheme.black,
              fontSize: 16,
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 14),
          Row(
            children: [
              Expanded(
                child: _optionButton(
                  title: 'Saved Address',
                  icon: Icons.home_outlined,
                  value: 'saved',
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: _optionButton(
                  title: 'New Address',
                  icon: Icons.add_location_alt_outlined,
                  value: 'manual',
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _optionButton({
    required String title,
    required IconData icon,
    required String value,
  }) {
    final bool isSelected = selectedLocationOption == value;

    return InkWell(
      borderRadius: BorderRadius.circular(12),
      onTap: () {
        setState(() {
          selectedLocationOption = value;
        });
      },
      child: Container(
        padding: const EdgeInsets.symmetric(vertical: 14, horizontal: 10),
        decoration: BoxDecoration(
          color: isSelected
              ? AppTheme.primaryRed.withOpacity(0.08)
              : AppTheme.white,
          borderRadius: BorderRadius.circular(12),
          border: Border.all(
            color: isSelected ? AppTheme.primaryRed : AppTheme.borderGray,
            width: 1.2,
          ),
        ),
        child: Column(
          children: [
            Icon(
              icon,
              color: isSelected ? AppTheme.primaryRed : AppTheme.gray,
              size: 26,
            ),
            const SizedBox(height: 8),
            Text(
              title,
              textAlign: TextAlign.center,
              style: TextStyle(
                color: isSelected ? AppTheme.primaryRed : AppTheme.black,
                fontSize: 13,
                fontWeight: FontWeight.bold,
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _savedAddressCard() {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(16),
      decoration: AppTheme.borderedCardDecoration,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            'Choose Saved Address',
            style: TextStyle(
              color: AppTheme.black,
              fontSize: 16,
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 12),
          ...savedAddresses.map((item) {
            final String type = item['type'] ?? '';
            final String address = item['address'] ?? '';
            final bool isSelected = selectedSavedAddress == address;

            return _savedAddressItem(
              type: type,
              address: address,
              isSelected: isSelected,
            );
          }),
        ],
      ),
    );
  }

  Widget _savedAddressItem({
    required String type,
    required String address,
    required bool isSelected,
  }) {
    return InkWell(
      borderRadius: BorderRadius.circular(12),
      onTap: () {
        setState(() {
          selectedSavedAddress = address;
        });
      },
      child: Container(
        margin: const EdgeInsets.only(bottom: 10),
        padding: const EdgeInsets.all(14),
        decoration: BoxDecoration(
          color: isSelected
              ? AppTheme.primaryRed.withOpacity(0.08)
              : AppTheme.lightGray,
          borderRadius: BorderRadius.circular(12),
          border: Border.all(
            color: isSelected ? AppTheme.primaryRed : AppTheme.borderGray,
          ),
        ),
        child: Row(
          children: [
            CircleAvatar(
              radius: 20,
              backgroundColor: AppTheme.white,
              child: Icon(
                Icons.location_on_outlined,
                color: isSelected ? AppTheme.primaryRed : AppTheme.gray,
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    type,
                    style: const TextStyle(
                      color: AppTheme.black,
                      fontSize: 13,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  const SizedBox(height: 3),
                  Text(
                    address,
                    style: const TextStyle(
                      color: AppTheme.gray,
                      fontSize: 13,
                      height: 1.3,
                    ),
                  ),
                ],
              ),
            ),
            Radio<String>(
              value: address,
              groupValue: selectedSavedAddress,
              activeColor: AppTheme.primaryRed,
              onChanged: (value) {
                setState(() {
                  selectedSavedAddress = value;
                });
              },
            ),
          ],
        ),
      ),
    );
  }

  Widget _manualAddressCard() {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(16),
      decoration: AppTheme.borderedCardDecoration,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            'Enter Service Location',
            style: TextStyle(
              color: AppTheme.black,
              fontSize: 16,
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 12),
          TextField(
            controller: manualAddressController,
            maxLines: 4,
            textCapitalization: TextCapitalization.words,
            decoration: const InputDecoration(
              labelText: 'Complete Address',
              hintText: 'House no., street, barangay, city, province',
              alignLabelWithHint: true,
            ),
          ),
          const SizedBox(height: 12),
          Container(
            width: double.infinity,
            padding: const EdgeInsets.all(12),
            decoration: AppTheme.softCardDecoration,
            child: const Text(
              'Note: This address is used only for this booking unless you save it later in your profile.',
              style: TextStyle(
                color: AppTheme.gray,
                fontSize: 12,
                height: 1.3,
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _bottomButton() {
    return Container(
      padding: const EdgeInsets.fromLTRB(20, 14, 20, 20),
      decoration: const BoxDecoration(
        color: AppTheme.white,
        border: Border(
          top: BorderSide(
            color: AppTheme.borderGray,
            width: 1,
          ),
        ),
      ),
      child: ElevatedButton(
        onPressed: _continueToProblem,
        child: const Text('Continue'),
      ),
    );
  }
}