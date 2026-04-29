import 'package:flutter/material.dart';
import 'package:mobile_app/app/theme.dart';
import 'package:mobile_app/shared/widgets/navigation/app_drawer.dart';
import 'package:mobile_app/features/profiles/widgets/profile_header.dart';
import 'package:mobile_app/features/profiles/widgets/profile_menu_list.dart';
import 'package:mobile_app/features/profiles/pages/client_edit_profile_page.dart';
import 'package:mobile_app/shared/shared.dart';

class ClientProfilePage extends StatelessWidget {
  final String email;

  const ClientProfilePage({super.key, required this.email});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      drawer: AppDrawer(
        userType: 3,
        email: email,
        currentPage: 'profile',
      ),
      appBar: const AppTitleHeader(
        title: "My Profile",
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          children: [
            ProfileHeader(
              name: "Sean Barte",
              role: "Client",
              email: email,
              onEdit: () {
                // TODO: Navigate to client edit profile page
              },
            ),

            const SizedBox(height: 28),

            ProfileMenuList(
            title: "Account",
            items: [
              ProfileMenuItem(
              icon: Icons.person_outline,
              title: "Edit Profile",
              subtitle: "Manage your personal information",
              onTap: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (_) => ClientEditProfilePage(email: email),
                  ),
                );
              },
            ),
              ProfileMenuItem(
                icon: Icons.location_on_outlined,
                title: "Address",
                subtitle: "Manage your service location",
                onTap: () {
                  // TODO: Navigate to address page
                },
              ),
              ProfileMenuItem(
                icon: Icons.calendar_month_outlined,
                title: "My Appointments",
                subtitle: "View your upcoming services",
                onTap: () {},
              ),
              ProfileMenuItem(
                icon: Icons.history_outlined,
                title: "Service History",
                subtitle: "Review your past services",
                onTap: () {},
              ),
            ],
          ),

          const SizedBox(height: 24),

          ProfileMenuList(
            title: "Settings",
            items: [
              ProfileMenuItem(
                icon: Icons.settings_outlined,
                title: "Settings",
                subtitle: "App preferences and notifications",
                onTap: () {},
              ),
              ProfileMenuItem(
                icon: Icons.privacy_tip_outlined,
                title: "Privacy & Security",
                subtitle: "Manage your privacy settings",
                onTap: () {},
              ),
              ProfileMenuItem(
                icon: Icons.logout_rounded,
                title: "Log Out",
                subtitle: "Sign out from your account",
                color: Colors.red,
                onTap: () {},
              ),
            ],
          ),
          ],
        ),
      ),
    );
  }
}