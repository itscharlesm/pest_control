import 'package:flutter/material.dart';
import 'package:mobile_app/app/theme.dart';
import 'package:mobile_app/features/bookings/widgets/booking_step_indicator.dart';
import 'package:mobile_app/shared/widgets/headers/app_back_header.dart';

class ClientBookingReviewPage extends StatefulWidget {
  final String email;

  const ClientBookingReviewPage({
    super.key,
    required this.email,
  });

  @override
  State<ClientBookingReviewPage> createState() =>
      _ClientBookingReviewPageState();
}

class _ClientBookingReviewPageState extends State<ClientBookingReviewPage> {
  bool isSubmitting = false;

  void _submitBooking() {
    setState(() => isSubmitting = true);

    Future.delayed(const Duration(milliseconds: 900), () {
      if (!mounted) return;

      setState(() => isSubmitting = false);

      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Booking request submitted for admin approval.'),
        ),
      );
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.lightGray,
      appBar: const AppBackHeader(
        title: 'Review Booking',
      ),
      body: Column(
        children: [
          const BookingStepIndicator(currentStep: 4),
          Expanded(
            child: SingleChildScrollView(
              padding: const EdgeInsets.fromLTRB(20, 18, 20, 24),
              child: Container(
                width: double.infinity,
                padding: const EdgeInsets.all(18),
                decoration: AppTheme.borderedCardDecoration,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    _reviewHeader(),
                    const SizedBox(height: 18),
                    _summaryBox(
                      icon: Icons.location_on_outlined,
                      title: 'Location',
                      children: const [
                        _MainValue('Office'),
                        SizedBox(height: 6),
                        _SubValue(
                          'Go Forward Pest Control Building, Genesis Street, Matina Crossing, Davao City',
                        ),
                      ],
                    ),
                    const SizedBox(height: 14),
                    _summaryBox(
                      icon: Icons.bug_report_outlined,
                      title: 'Pest Problem',
                      children: const [
                        _MainValue('Ants'),
                        SizedBox(height: 6),
                        _SubValue(
                          'Pests are seen mostly in the kitchen and living room area.',
                        ),
                      ],
                    ),
                    const SizedBox(height: 14),
                    _summaryBox(
                      icon: Icons.home_repair_service_outlined,
                      title: 'Treatment Areas',
                      children: [
                        _areaChip('Kitchen', '₱25'),
                        const SizedBox(height: 8),
                        _areaChip('Bathroom', '₱15'),
                        const Divider(height: 24),
                        _totalRow('Estimated Total', '₱40'),
                      ],
                    ),
                    const SizedBox(height: 14),
                    _summaryBox(
                      icon: Icons.calendar_month_outlined,
                      title: 'Appointment',
                      children: const [
                        _MainValue('Friday, May 29, 2026'),
                        SizedBox(height: 6),
                        _SubValue('8:00 AM - 12:00 PM'),
                      ],
                    ),
                    const SizedBox(height: 16),
                    _nextStepBox(),
                  ],
                ),
              ),
            ),
          ),
          _bottomButton(),
        ],
      ),
    );
  }

  Widget _reviewHeader() {
    return const Row(
      children: [
        Icon(
          Icons.check_rounded,
          color: AppTheme.primaryRed,
          size: 22,
        ),
        SizedBox(width: 10),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                'Review Your Request',
                style: TextStyle(
                  color: AppTheme.black,
                  fontSize: 17,
                  fontWeight: FontWeight.bold,
                ),
              ),
              SizedBox(height: 4),
              Text(
                'Please confirm the details before submitting.',
                style: TextStyle(
                  color: AppTheme.gray,
                  fontSize: 13,
                ),
              ),
            ],
          ),
        ),
      ],
    );
  }

  Widget _summaryBox({
    required IconData icon,
    required String title,
    required List<Widget> children,
  }) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(14),
      decoration: AppTheme.softCardDecoration,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Icon(
                icon,
                color: AppTheme.gray,
                size: 18,
              ),
              const SizedBox(width: 8),
              Text(
                title,
                style: const TextStyle(
                  color: AppTheme.gray,
                  fontSize: 13,
                  fontWeight: FontWeight.w600,
                ),
              ),
            ],
          ),
          const SizedBox(height: 10),
          ...children,
        ],
      ),
    );
  }

  Widget _areaChip(String area, String price) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 7),
      decoration: BoxDecoration(
        color: AppTheme.white,
        borderRadius: BorderRadius.circular(8),
        border: Border.all(color: AppTheme.borderGray),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Text(
            area,
            style: const TextStyle(
              color: AppTheme.black,
              fontSize: 13,
              fontWeight: FontWeight.w600,
            ),
          ),
          const SizedBox(width: 6),
          Text(
            price,
            style: const TextStyle(
              color: AppTheme.primaryRed,
              fontSize: 13,
              fontWeight: FontWeight.bold,
            ),
          ),
        ],
      ),
    );
  }

  Widget _totalRow(String label, String amount) {
    return Row(
      children: [
        Expanded(
          child: Text(
            label,
            style: const TextStyle(
              color: AppTheme.gray,
              fontSize: 13,
              fontWeight: FontWeight.w600,
            ),
          ),
        ),
        Text(
          amount,
          style: const TextStyle(
            color: AppTheme.primaryRed,
            fontSize: 20,
            fontWeight: FontWeight.bold,
          ),
        ),
      ],
    );
  }

  Widget _nextStepBox() {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: const Color(0xFFEAF2FF),
        borderRadius: BorderRadius.circular(12),
      ),
      child: const Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            'What happens next?',
            style: TextStyle(
              color: Color(0xFF0B3CC1),
              fontSize: 13,
              fontWeight: FontWeight.bold,
            ),
          ),
          SizedBox(height: 5),
          Text(
            'The admin will review your request and confirm technician availability. You will be notified once your booking is approved.',
            style: TextStyle(
              color: Color(0xFF0B3CC1),
              fontSize: 13,
              height: 1.4,
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
      child: LoadingButton(
        isLoading: isSubmitting,
        onPressed: _submitBooking,
        child: const Text('Submit for Approval'),
      ),
    );
  }
}

class _MainValue extends StatelessWidget {
  final String text;

  const _MainValue(this.text);

  @override
  Widget build(BuildContext context) {
    return Text(
      text,
      style: const TextStyle(
        color: AppTheme.black,
        fontSize: 15,
        fontWeight: FontWeight.bold,
      ),
    );
  }
}

class _SubValue extends StatelessWidget {
  final String text;

  const _SubValue(this.text);

  @override
  Widget build(BuildContext context) {
    return Text(
      text,
      style: const TextStyle(
        color: AppTheme.black,
        fontSize: 13,
        height: 1.35,
      ),
    );
  }
}