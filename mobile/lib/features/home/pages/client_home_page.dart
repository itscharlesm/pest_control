import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:mobile_app/app/theme.dart';
import 'package:mobile_app/config/api_config.dart';
import 'package:mobile_app/shared/shared.dart';

class ClientHomePage extends StatefulWidget {
  final String email;

  const ClientHomePage({
    super.key,
    required this.email,
  });

  @override
  State<ClientHomePage> createState() => _ClientHomePageState();
}

class _ClientHomePageState extends State<ClientHomePage> {
  String? profileImagePath;

  @override
  void initState() {
    super.initState();
    _fetchProfileImage();
  }

  Future<void> _fetchProfileImage() async {
    try {
      final response = await http.post(
        Uri.parse('${ApiConfig.baseUrl}/api/mobile/profile'),
        headers: {
          'Accept': 'application/json',
        },
        body: {
          'email': widget.email,
        },
      );

      final data = jsonDecode(response.body);

      if (!mounted) return;

      if (response.statusCode == 200 && data['success'] == true) {
        final user = data['data'];

        setState(() {
          profileImagePath = user['usr_image_path'];
        });
      }
    } catch (_) {
      // Keep default avatar if fetching fails.
    }
  }

  String? get profileImageUrl {
    if (profileImagePath == null || profileImagePath!.isEmpty) {
      return null;
    }

    return '${ApiConfig.baseUrl}/$profileImagePath';
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      drawer: AppDrawer(
        userType: 3,
        email: widget.email,
        currentPage: 'home',
      ),
      appBar: AppHomeHeader(
        imageUrl: profileImageUrl,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              "Welcome",
              style: Theme.of(context).textTheme.bodySmall,
            ),
            const SizedBox(height: 4),
            Text(
              widget.email,
              style: Theme.of(context).textTheme.titleLarge,
            ),

            const SizedBox(height: 24),

            ElevatedButton.icon(
              onPressed: () {
                // TODO: navigate to booking page
              },
              icon: const Icon(Icons.add),
              label: const Text("Book a Service"),
            ),

            const SizedBox(height: 24),

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
              decoration: AppTheme.softCardDecoration,
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

  Widget _serviceCard(String title, IconData icon) {
    return Expanded(
      child: Container(
        height: 100,
        decoration: AppTheme.softCardDecoration,
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