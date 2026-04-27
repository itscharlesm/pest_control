import 'package:flutter/material.dart';
import 'package:mobile_app/app/theme.dart';

class AppDrawer extends StatelessWidget {
  final int userType; // 3 = Client, 2 = Technician
  final String email;
  final String? name;
  final VoidCallback? onLogout;

  const AppDrawer({
    super.key,
    required this.userType,
    required this.email,
    this.name,
    this.onLogout,
  });

  @override
  Widget build(BuildContext context) {
    final bool isClient = userType == 3;
    final bool isTechnician = userType == 2;

    return Drawer(
      backgroundColor: AppTheme.white,
      child: SafeArea(
        child: Column(
          children: [
            _drawerHeader(isClient, isTechnician),

            Expanded(
              child: ListView(
                padding: EdgeInsets.zero,
                children: [
                  _drawerItem(
                    icon: Icons.home_outlined,
                    title: 'Home',
                    onTap: () => Navigator.pop(context),
                  ),

                  if (isClient) ...[
                    _drawerItem(
                      icon: Icons.add_circle_outline,
                      title: 'Book Service',
                      onTap: () {
                        Navigator.pop(context);
                        // TODO: Navigate to book service page
                      },
                    ),
                    _drawerItem(
                      icon: Icons.calendar_month_outlined,
                      title: 'My Appointments',
                      onTap: () {
                        Navigator.pop(context);
                        // TODO: Navigate to appointments page
                      },
                    ),
                    _drawerItem(
                      icon: Icons.history_outlined,
                      title: 'Service History',
                      onTap: () {
                        Navigator.pop(context);
                        // TODO: Navigate to service history page
                      },
                    ),
                  ],

                  if (isTechnician) ...[
                    _drawerItem(
                      icon: Icons.assignment_outlined,
                      title: 'Assigned Jobs',
                      onTap: () {
                        Navigator.pop(context);
                        // TODO: Navigate to assigned jobs page
                      },
                    ),
                    _drawerItem(
                      icon: Icons.location_on_outlined,
                      title: 'Job Locations',
                      onTap: () {
                        Navigator.pop(context);
                        // TODO: Navigate to job locations page
                      },
                    ),
                    _drawerItem(
                      icon: Icons.edit_note_outlined,
                      title: 'Service Reports',
                      onTap: () {
                        Navigator.pop(context);
                        // TODO: Navigate to reports page
                      },
                    ),
                  ],

                  const Divider(height: 24),

                  _drawerItem(
                    icon: Icons.person_outline,
                    title: 'Profile',
                    onTap: () {
                      Navigator.pop(context);
                      // TODO: Navigate to profile page
                    },
                  ),
                  _drawerItem(
                    icon: Icons.settings_outlined,
                    title: 'Settings',
                    onTap: () {
                      Navigator.pop(context);
                      // TODO: Navigate to settings page
                    },
                  ),
                ],
              ),
            ),

            const Divider(height: 1),

            _drawerItem(
              icon: Icons.logout,
              title: 'Logout',
              color: AppTheme.primaryRed,
              onTap: onLogout ??
                  () {
                    Navigator.pop(context);
                    // TODO: Add logout logic
                  },
            ),

            const SizedBox(height: 12),
          ],
        ),
      ),
    );
  }

  Widget _drawerHeader(bool isClient, bool isTechnician) {
    final String roleText = isClient
        ? 'Client'
        : isTechnician
            ? 'Technician'
            : 'User';

    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(20),
      decoration: const BoxDecoration(
        color: AppTheme.primaryRed,
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const CircleAvatar(
            radius: 28,
            backgroundColor: Colors.white,
            child: Icon(
              Icons.person,
              color: AppTheme.primaryRed,
              size: 32,
            ),
          ),
          const SizedBox(height: 12),
          Text(
            name?.isNotEmpty == true ? name! : roleText,
            style: const TextStyle(
              color: AppTheme.white,
              fontSize: 18,
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            email,
            style: const TextStyle(
              color: Colors.white70,
              fontSize: 13,
            ),
          ),
          const SizedBox(height: 6),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
            decoration: BoxDecoration(
              color: Colors.white.withOpacity(0.18),
              borderRadius: BorderRadius.circular(20),
            ),
            child: Text(
              roleText,
              style: const TextStyle(
                color: AppTheme.white,
                fontSize: 12,
                fontWeight: FontWeight.w600,
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _drawerItem({
    required IconData icon,
    required String title,
    required VoidCallback onTap,
    Color? color,
  }) {
    final itemColor = color ?? AppTheme.black;

    return ListTile(
      leading: Icon(
        icon,
        color: itemColor,
      ),
      title: Text(
        title,
        style: TextStyle(
          color: itemColor,
          fontWeight: FontWeight.w600,
        ),
      ),
      onTap: onTap,
    );
  }
}