import 'package:flutter/material.dart';

class DashboardPage extends StatelessWidget {
  final String email;

  const DashboardPage({super.key, required this.email});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("Dashboard"),
      ),
      body: Center(
        child: Text(
          "Welcome, $email",
          style: const TextStyle(fontSize: 18),
        ),
      ),
    );
  }
}