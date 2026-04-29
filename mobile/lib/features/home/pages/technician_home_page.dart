import 'package:flutter/material.dart';
import 'package:mobile_app/app/theme.dart';
import 'package:mobile_app/shared/widgets/navigation/app_drawer.dart';
import 'package:mobile_app/features/auth/pages/login_page.dart';

class TechnicianHomePage extends StatelessWidget {
  final String email;

  const TechnicianHomePage({
    super.key,
    required this.email,
  });

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      drawer: AppDrawer(
        userType: 2,
        email: email,
        currentPage: 'home',
        onLogout: () {
          Navigator.pushAndRemoveUntil(
            context,
            MaterialPageRoute(builder: (_) => const LoginPage()),
            (route) => false,
          );
        },
      ),
      appBar: AppBar(
        title: const Text("Technician Home"),
        actions: [
          IconButton(
            onPressed: () {
              // TODO: logout
            },
            icon: const Icon(Icons.logout),
          ),
        ],
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text("Welcome, Technician", style: Theme.of(context).textTheme.bodySmall),
            const SizedBox(height: 4),
            Text(email, style: Theme.of(context).textTheme.titleLarge),

            const SizedBox(height: 24),

            Row(
              children: [
                _summaryCard("Assigned Jobs", "0", Icons.assignment),
                const SizedBox(width: 10),
                _summaryCard("Completed", "0", Icons.check_circle),
              ],
            ),

            const SizedBox(height: 24),

            Text(
              "Today’s Assignment",
              style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                    fontWeight: FontWeight.bold,
                  ),
            ),

            const SizedBox(height: 12),

            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(16),
              decoration: AppTheme.softCardDecoration,
              child: const Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    "No assigned job yet.",
                    style: TextStyle(
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                  SizedBox(height: 6),
                  Text(
                    "Assigned service requests will appear here once approved by the admin.",
                    style: TextStyle(color: Colors.grey),
                  ),
                ],
              ),
            ),

            const SizedBox(height: 24),

            Text(
              "Quick Actions",
              style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                    fontWeight: FontWeight.bold,
                  ),
            ),

            const SizedBox(height: 12),

            ElevatedButton.icon(
              onPressed: () {
                // TODO: navigate to assigned jobs page
              },
              icon: const Icon(Icons.list_alt),
              label: const Text("View Assigned Jobs"),
            ),

            const SizedBox(height: 12),

            OutlinedButton.icon(
              onPressed: () {
                // TODO: navigate to service report page
              },
              icon: const Icon(Icons.edit_note),
              label: const Text("Submit Service Report"),
            ),
          ],
        ),
      ),
    );
  }

  Widget _summaryCard(String title, String value, IconData icon) {
    return Expanded(
      child: Container(
        height: 110,
        padding: const EdgeInsets.all(14),
        decoration: AppTheme.softCardDecoration,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Icon(icon, size: 28),
            const Spacer(),
            Text(
              value,
              style: const TextStyle(
                fontSize: 24,
                fontWeight: FontWeight.bold,
              ),
            ),
            Text(
              title,
              style: const TextStyle(
                fontSize: 13,
                color: Colors.grey,
              ),
            ),
          ],
        ),
      ),
    );
  }
}