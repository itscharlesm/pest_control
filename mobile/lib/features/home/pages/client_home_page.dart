import 'package:flutter/material.dart';
import 'package:mobile_app/app/theme.dart';
import 'package:mobile_app/shared/widgets/navigation/app_drawer.dart';

class ClientHomePage extends StatelessWidget {
  final String email;

  const ClientHomePage({super.key, required this.email});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      drawer: AppDrawer( 
        userType: 3,
        email: email,
      ),
      appBar: AppBar(
        title: const Text("Client Home"),
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
            // 🔥 Greeting
            Text(
              "Welcome",
              style: Theme.of(context).textTheme.bodySmall,
            ),
            const SizedBox(height: 4),
            Text(
              email,
              style: Theme.of(context).textTheme.titleLarge,
            ),

            const SizedBox(height: 24),

            // 🔥 Quick Action
            ElevatedButton.icon(
              onPressed: () {
                // TODO: navigate to booking page
              },
              icon: const Icon(Icons.add),
              label: const Text("Book a Service"),
            ),

            const SizedBox(height: 24),

            // 🔥 Services Section
            Text(
              "Available Services",
              style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                    fontWeight: FontWeight.bold,
                  ),
            ),

            const SizedBox(height: 12),

            Row(
              children: [
                _serviceCard("Termite", Icons.bug_report),
                const SizedBox(width: 10),
                _serviceCard("Mosquito", Icons.pest_control),
              ],
            ),

            const SizedBox(height: 24),

            // 🔥 Booking Section
            Text(
              "My Bookings",
              style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                    fontWeight: FontWeight.bold,
                  ),
            ),

            const SizedBox(height: 12),

            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: AppTheme.lightGray,
                borderRadius: BorderRadius.circular(10),
              ),
              child: const Text(
                "No bookings yet.",
                style: TextStyle(color: Colors.grey),
              ),
            ),
          ],
        ),
      ),
    );
  }

  // 🔥 reusable service card
  Widget _serviceCard(String title, IconData icon) {
    return Expanded(
      child: Container(
        height: 100,
        decoration: BoxDecoration(
          color: AppTheme.lightGray,
          borderRadius: BorderRadius.circular(10),
        ),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(icon, size: 30),
            const SizedBox(height: 6),
            Text(title),
          ],
        ),
      ),
    );
  }
}