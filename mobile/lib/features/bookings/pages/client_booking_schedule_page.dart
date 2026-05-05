import 'package:flutter/material.dart';
import 'package:mobile_app/app/theme.dart';
import 'package:mobile_app/features/bookings/widgets/booking_step_indicator.dart';
import 'package:mobile_app/shared/widgets/headers/app_back_header.dart';
import 'package:mobile_app/features/bookings/pages/client_booking_review_page.dart';

class ClientBookingSchedulePage extends StatefulWidget {
  final String email;

  const ClientBookingSchedulePage({
    super.key,
    required this.email,
  });

  @override
  State<ClientBookingSchedulePage> createState() =>
      _ClientBookingSchedulePageState();
}

class _ClientBookingSchedulePageState extends State<ClientBookingSchedulePage> {
  DateTime? selectedDate;
  String? selectedTime;
  String? selectedUrgency;

  final List<String> timeSlots = [
    '8:00 AM',
    '9:00 AM',
    '10:00 AM',
    '11:00 AM',
    '1:00 PM',
    '2:00 PM',
    '3:00 PM',
    '4:00 PM',
  ];

  final List<String> urgencyOptions = [
    'Flexible',
    'Within 3 days',
    'ASAP',
  ];

  Future<void> _pickDate() async {
    final DateTime now = DateTime.now();

    final DateTime? date = await showDatePicker(
      context: context,
      initialDate: selectedDate ?? now,
      firstDate: now,
      lastDate: DateTime(now.year + 1),
    );

    if (date == null) return;

    setState(() {
      selectedDate = date;
    });
  }

  void _continueToReview() {
    if (selectedDate == null ||
        selectedTime == null ||
        selectedUrgency == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Please complete your preferred schedule.'),
        ),
      );
      return;
    }
    Navigator.push(
      context,
      MaterialPageRoute(
        builder: (_) => ClientBookingReviewPage(
          email: widget.email,
        ),
      ),
    );
  }

  String get formattedDate {
    if (selectedDate == null) return 'Select preferred date';

    return '${selectedDate!.month}/${selectedDate!.day}/${selectedDate!.year}';
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
          const BookingStepIndicator(currentStep: 3),
          Expanded(
            child: SingleChildScrollView(
              padding: const EdgeInsets.fromLTRB(20, 18, 20, 24),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  _introCard(),
                  const SizedBox(height: 18),
                  _dateCard(),
                  const SizedBox(height: 18),
                  _timeCard(),
                  const SizedBox(height: 18),
                  _urgencyCard(),
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
            'When do you need the service?',
            style: TextStyle(
              color: AppTheme.black,
              fontSize: 18,
              fontWeight: FontWeight.bold,
            ),
          ),
          SizedBox(height: 8),
          Text(
            'Choose your preferred service date and time. The company may still confirm the final schedule depending on technician availability.',
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

  Widget _dateCard() {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(16),
      decoration: AppTheme.borderedCardDecoration,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            'Preferred Date',
            style: TextStyle(
              color: AppTheme.black,
              fontSize: 16,
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 12),
          InkWell(
            borderRadius: BorderRadius.circular(12),
            onTap: _pickDate,
            child: Container(
              width: double.infinity,
              padding: const EdgeInsets.all(14),
              decoration: AppTheme.softCardDecoration,
              child: Row(
                children: [
                  const Icon(
                    Icons.calendar_month_outlined,
                    color: AppTheme.primaryRed,
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: Text(
                      formattedDate,
                      style: TextStyle(
                        color: selectedDate == null
                            ? AppTheme.gray
                            : AppTheme.black,
                        fontSize: 14,
                        fontWeight: selectedDate == null
                            ? FontWeight.w500
                            : FontWeight.bold,
                      ),
                    ),
                  ),
                  const Icon(
                    Icons.chevron_right_rounded,
                    color: AppTheme.gray,
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _timeCard() {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(16),
      decoration: AppTheme.borderedCardDecoration,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            'Preferred Time',
            style: TextStyle(
              color: AppTheme.black,
              fontSize: 16,
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 12),
          GridView.builder(
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            itemCount: timeSlots.length,
            gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
              crossAxisCount: 2,
              crossAxisSpacing: 10,
              mainAxisSpacing: 10,
              childAspectRatio: 2.8,
            ),
            itemBuilder: (context, index) {
              final time = timeSlots[index];
              final isSelected = selectedTime == time;

              return InkWell(
                borderRadius: BorderRadius.circular(12),
                onTap: () {
                  setState(() {
                    selectedTime = time;
                  });
                },
                child: Container(
                  alignment: Alignment.center,
                  decoration: BoxDecoration(
                    color: isSelected
                        ? AppTheme.primaryRed.withOpacity(0.08)
                        : AppTheme.white,
                    borderRadius: BorderRadius.circular(12),
                    border: Border.all(
                      color: isSelected
                          ? AppTheme.primaryRed
                          : AppTheme.borderGray,
                    ),
                  ),
                  child: Text(
                    time,
                    style: TextStyle(
                      color:
                          isSelected ? AppTheme.primaryRed : AppTheme.black,
                      fontWeight:
                          isSelected ? FontWeight.bold : FontWeight.w600,
                    ),
                  ),
                ),
              );
            },
          ),
        ],
      ),
    );
  }

  Widget _urgencyCard() {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(16),
      decoration: AppTheme.borderedCardDecoration,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            'Urgency',
            style: TextStyle(
              color: AppTheme.black,
              fontSize: 16,
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 12),
          ...urgencyOptions.map((option) {
            final isSelected = selectedUrgency == option;

            return InkWell(
              borderRadius: BorderRadius.circular(12),
              onTap: () {
                setState(() {
                  selectedUrgency = option;
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
                    color: isSelected
                        ? AppTheme.primaryRed
                        : AppTheme.borderGray,
                  ),
                ),
                child: Row(
                  children: [
                    Icon(
                      isSelected
                          ? Icons.radio_button_checked
                          : Icons.radio_button_off,
                      color:
                          isSelected ? AppTheme.primaryRed : AppTheme.gray,
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: Text(
                        option,
                        style: TextStyle(
                          color: isSelected
                              ? AppTheme.primaryRed
                              : AppTheme.black,
                          fontWeight: isSelected
                              ? FontWeight.bold
                              : FontWeight.w600,
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            );
          }),
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
        onPressed: _continueToReview,
        child: const Text('Continue'),
      ),
    );
  }
}