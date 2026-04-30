import 'package:flutter/material.dart';
import 'package:mobile_app/app/theme.dart';
import 'package:mobile_app/shared/widgets/navigation/app_drawer.dart';
import 'package:mobile_app/features/profiles/widgets/profile_header.dart';
import 'package:mobile_app/features/profiles/widgets/profile_info_card.dart';
import 'package:mobile_app/features/profiles/widgets/profile_menu_list.dart';

class TechnicianProfilePage extends StatelessWidget {
  final String email;

  const TechnicianProfilePage({super.key, required this.email});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      drawer: AppDrawer(
        userType: 2,
        email: email,
        currentPage: 'profile',
      ),
      appBar: _profileAppBar(context, "My Profile"),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          children: [
            ProfileHeader(
              name: "Technician",
              role: "Technician",
              email: email,
              onEdit: () {
                // TODO: Navigate to technician edit profile page
              },
            ),
            const SizedBox(height: 22),
            const ProfileInfoCard(
              phone: "09XXXXXXXXX",
              address: "Assigned Branch / Area",
            ),
            const SizedBox(height: 26),
            ProfileMenuList(
              title: "Work",
              items: [
                ProfileMenuItem(
                  icon: Icons.assignment_outlined,
                  title: "Assigned Jobs",
                  subtitle: "View service requests assigned to you",
                  onTap: () {},
                ),
                ProfileMenuItem(
                  icon: Icons.edit_note_outlined,
                  title: "Service Reports",
                  subtitle: "Create and submit service reports",
                  onTap: () {},
                ),
                ProfileMenuItem(
                  icon: Icons.location_on_outlined,
                  title: "Job Locations",
                  subtitle: "View client service locations",
                  onTap: () {},
                ),
              ],
            ),
            const SizedBox(height: 16),
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
                  color: AppTheme.primaryRed,
                  onTap: () {},
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  PreferredSizeWidget _profileAppBar(BuildContext context, String title) {
    return AppBar(
      automaticallyImplyLeading: false,
      backgroundColor: Colors.white,
      elevation: 0,
      centerTitle: true,
      title: Text(
        title,
        style: const TextStyle(
          color: AppTheme.primaryRed,
          fontWeight: FontWeight.bold,
        ),
      ),
      leading: Builder(
        builder: (context) {
          return IconButton(
            icon: const Icon(Icons.menu_rounded, color: AppTheme.black),
            onPressed: () => Scaffold.of(context).openDrawer(),
          );
        },
      ),
      actions: [
        IconButton(
          icon: const Icon(Icons.notifications_none, color: AppTheme.black),
          onPressed: () {},
        ),
      ],
    );
  }
}