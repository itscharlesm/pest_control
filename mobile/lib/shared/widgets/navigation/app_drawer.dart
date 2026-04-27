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
      child: Column(
        children: [
          _drawerTopImage(),
          _profileSection(isClient, isTechnician),

          Expanded(
            child: ListView(
              padding: const EdgeInsets.fromLTRB(12, 0, 12, 0),
              children: [
                _drawerItem(
                  icon: Icons.home_rounded,
                  title: 'Home',
                  isSelected: true,
                  onTap: () => Navigator.pop(context),
                ),

                _drawerItem(
                  icon: Icons.person_rounded,
                  title: 'Profile',
                  onTap: () {
                    Navigator.pop(context);
                    // TODO: Navigate to Profile Page
                  },
                ),

                if (isClient) ...[
                  _drawerItem(
                    icon: Icons.add_circle_outline_rounded,
                    title: 'Book Service',
                    onTap: () => Navigator.pop(context),
                  ),
                  _drawerItem(
                    icon: Icons.calendar_month_rounded,
                    title: 'My Appointments',
                    onTap: () => Navigator.pop(context),
                  ),
                  _drawerItem(
                    icon: Icons.history_rounded,
                    title: 'Service History',
                    onTap: () => Navigator.pop(context),
                  ),
                ],

                if (isTechnician) ...[
                  _drawerItem(
                    icon: Icons.assignment_rounded,
                    title: 'Assigned Jobs',
                    onTap: () => Navigator.pop(context),
                  ),
                  _drawerItem(
                    icon: Icons.location_on_rounded,
                    title: 'Job Locations',
                    onTap: () => Navigator.pop(context),
                  ),
                  _drawerItem(
                    icon: Icons.edit_note_rounded,
                    title: 'Service Reports',
                    onTap: () => Navigator.pop(context),
                  ),
                ],

                _drawerItem(
                  icon: Icons.settings_rounded,
                  title: 'Settings',
                  onTap: () => Navigator.pop(context),
                ),
              ],
            ),
          ),

          _divider(),

          Padding(
            padding: const EdgeInsets.fromLTRB(12, 0, 12, 40),
            child: _drawerItem(
              icon: Icons.logout_rounded,
              title: 'Logout',
              color: AppTheme.primaryRed,
              onTap: onLogout ??
                  () {
                    Navigator.pop(context);
                  },
            ),
          ),
        ],
      ),
    );
  }

  Widget _drawerTopImage() {
    return SizedBox(
      height: 230,
      width: double.infinity,
      child: Stack(
        children: [
          Positioned.fill(
            child: Image.asset(
              'assets/images/img_drawerbg.png',
              fit: BoxFit.fill,
            ),
          ),
          Positioned(
            top: 42,
            left: 24,
            child: Image.asset(
              'assets/images/img_goforwardlogo.png',
              height: 125,
              fit: BoxFit.contain,
            ),
          ),
        ],
      ),
    );
  }

  Widget _profileSection(bool isClient, bool isTechnician) {
    final String roleText = isClient
        ? 'Sean Barte'
        : isTechnician
            ? 'Technician'
            : 'User';

    return Transform.translate(
      offset: const Offset(0, -28),
      child: Padding(
        padding: const EdgeInsets.fromLTRB(24, 0, 24, 0),
        child: Row(
          children: [
            const CircleAvatar(
              radius: 28,
              backgroundColor: Color.fromARGB(255, 233, 231, 231), // 👈 slightly darker gray
              child: Icon(
                Icons.person,
                color: AppTheme.primaryRed,
                size: 32,
              ),
            ),
            const SizedBox(width: 14),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    name?.isNotEmpty == true ? name! : roleText,  
                    style: const TextStyle(
                      color: AppTheme.black,
                      fontSize: 20,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  const SizedBox(height: 3),
                  Text(
                    email,
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                    style: const TextStyle(
                      color: AppTheme.gray,
                      fontSize: 13,
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _divider() {
    return const Padding(
      padding: EdgeInsets.fromLTRB(12, 8, 12, 8),
      child: Divider(height: 1),
    );
  }

  Widget _drawerItem({
    required IconData icon,
    required String title,
    required VoidCallback onTap,
    Color? color,
    bool isSelected = false,
  }) {
    final bgColor = isSelected
        ? Colors.black.withOpacity(0.40) // 👈 subtle black
        : Colors.transparent;

    final itemColor = isSelected
        ? Colors.white // 👈 white text when active
        : (color ?? AppTheme.black);

    final iconColor = isSelected
        ? Colors.white
        : (color ?? AppTheme.black);

    return Container(
      margin: const EdgeInsets.only(bottom: 6),
      decoration: BoxDecoration(
        color: bgColor,
        borderRadius: BorderRadius.circular(12),
      ),
      child: ListTile(
        dense: true,
        contentPadding:
            const EdgeInsets.symmetric(horizontal: 14, vertical: 2),
        leading: Icon(icon, color: iconColor),
        title: Text(
          title,
          style: TextStyle(
            color: itemColor,
            fontWeight: isSelected ? FontWeight.bold : FontWeight.w600,
          ),
        ),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(12),
        ),
        onTap: onTap,
      ),
    );
  }
}