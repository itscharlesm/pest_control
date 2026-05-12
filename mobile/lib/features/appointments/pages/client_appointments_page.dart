import 'package:flutter/material.dart';
import 'package:mobile_app/app/theme.dart';
import 'package:mobile_app/shared/shared.dart';
import 'package:mobile_app/shared/widgets/navigation/app_drawer.dart';

class ClientAppointmentsPage extends StatefulWidget {
  final String email;

  const ClientAppointmentsPage({
    super.key,
    required this.email,
  });

  @override
  State<ClientAppointmentsPage> createState() => _ClientAppointmentsPageState();
}

class _ClientAppointmentsPageState extends State<ClientAppointmentsPage> {
  bool isLoading = false;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.lightGray,
      drawer: AppDrawer(
        userType: 3,
        email: widget.email,
        currentPage: 'appointments',
      ),
      appBar: const AppTitleHeader(
        title: 'My Appointments',
      ),
      body: isLoading
          ? const Center(
              child: CircularProgressIndicator(
                color: AppTheme.primaryRed,
              ),
            )
          : SingleChildScrollView(
              padding: const EdgeInsets.all(20),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  _pageHeader(),
                  const SizedBox(height: 18),
                  _emptyAppointmentsCard(),
                ],
              ),
            ),
    );
  }

  Widget _pageHeader() {
    return const Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          'Your Service Requests',
          style: TextStyle(
            color: AppTheme.black,
            fontSize: 18,
            fontWeight: FontWeight.bold,
          ),
        ),
        SizedBox(height: 5),
        Text(
          'View and track your submitted appointment requests.',
          style: TextStyle(
            color: AppTheme.gray,
            fontSize: 13,
            height: 1.35,
          ),
        ),
      ],
    );
  }

  Widget _emptyAppointmentsCard() {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(22),
      decoration: AppTheme.borderedCardDecoration,
      child: Column(
        children: [
          Container(
            width: 54,
            height: 54,
            decoration: BoxDecoration(
              color: AppTheme.primaryRed.withOpacity(0.08),
              shape: BoxShape.circle,
            ),
            child: const Icon(
              Icons.calendar_month_outlined,
              color: AppTheme.primaryRed,
              size: 28,
            ),
          ),
          const SizedBox(height: 14),
          const Text(
            'No appointments yet',
            style: TextStyle(
              color: AppTheme.black,
              fontSize: 15,
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 6),
          const Text(
            'Your submitted service requests will appear here.',
            textAlign: TextAlign.center,
            style: TextStyle(
              color: AppTheme.gray,
              fontSize: 12,
              height: 1.35,
            ),
          ),
        ],
      ),
    );
  }
}