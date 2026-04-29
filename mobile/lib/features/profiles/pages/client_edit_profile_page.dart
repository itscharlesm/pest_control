import 'package:flutter/material.dart';
import 'package:mobile_app/app/theme.dart';
import 'package:mobile_app/shared/shared.dart';

class ClientEditProfilePage extends StatefulWidget {
  final String email;

  const ClientEditProfilePage({
    super.key,
    required this.email,
  });

  @override
  State<ClientEditProfilePage> createState() => _ClientEditProfilePageState();
}

class _ClientEditProfilePageState extends State<ClientEditProfilePage> {
  final TextEditingController nameController =
      TextEditingController(text: "Sean Barte");

  final TextEditingController phoneController =
      TextEditingController(text: "0926 685 5867");

  final TextEditingController addressController =
      TextEditingController(text: "Davao City, Davao del Sur, Philippines");

  final TextEditingController emailController = TextEditingController();

  @override
  void initState() {
    super.initState();
    emailController.text = widget.email;
  }

  @override
  void dispose() {
    nameController.dispose();
    phoneController.dispose();
    addressController.dispose();
    emailController.dispose();
    super.dispose();
  }

  void _saveProfile() {
    // Dummy save for now
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(
        content: Text("Profile updated successfully."),
      ),
    );

    Navigator.pop(context);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: const AppBackHeader(
        title: "Edit Profile",
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.fromLTRB(20, 22, 20, 30),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Center(
              child: Column(
                children: [
                  Stack(
                    children: [
                      const CircleAvatar(
                        radius: 46,
                        backgroundColor: AppTheme.primaryRed,
                        child: Icon(
                          Icons.person,
                          color: Colors.white,
                          size: 46,
                        ),
                      ),
                      Positioned(
                        bottom: 2,
                        right: 2,
                        child: Container(
                          padding: const EdgeInsets.all(6),
                          decoration: const BoxDecoration(
                            color: Colors.white,
                            shape: BoxShape.circle,
                          ),
                          child: const Icon(
                            Icons.camera_alt,
                            color: AppTheme.primaryRed,
                            size: 16,
                          ),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 8),
                  const Text(
                    "Tap to change photo",
                    style: TextStyle(
                      color: AppTheme.gray,
                      fontSize: 12,
                    ),
                  ),
                ],
              ),
            ),

            const SizedBox(height: 28),

            _profileInput(
              label: "Full Name",
              controller: nameController,
              icon: Icons.person_outline,
            ),

            const SizedBox(height: 18),

            _profileInput(
              label: "Email Address",
              controller: emailController,
              icon: Icons.email_outlined,
              readOnly: true,
            ),

            const SizedBox(height: 18),

            _profileInput(
              label: "Phone Number *",
              controller: phoneController,
              icon: Icons.phone_outlined,
              keyboardType: TextInputType.phone,
            ),

            const SizedBox(height: 18),

            _profileInput(
              label: "Address *",
              controller: addressController,
              icon: Icons.location_on_outlined,
              maxLines: 2,
            ),

            const SizedBox(height: 24),

            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: AppTheme.primaryRed.withOpacity(0.06),
                borderRadius: BorderRadius.circular(14),
              ),
              child: Row(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: const [
                  Icon(
                    Icons.shield_outlined,
                    color: AppTheme.primaryRed,
                    size: 28,
                  ),
                  SizedBox(width: 12),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          "Your information is secure",
                          style: TextStyle(
                            fontWeight: FontWeight.bold,
                            fontSize: 14,
                          ),
                        ),
                        SizedBox(height: 4),
                        Text(
                          "We protect your data and only use it to provide better service.",
                          style: TextStyle(
                            color: AppTheme.gray,
                            fontSize: 13,
                            height: 1.3,
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),

            const SizedBox(height: 28),

            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: _saveProfile,
                child: const Text("Save Changes"),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _profileInput({
    required String label,
    required TextEditingController controller,
    required IconData icon,
    bool readOnly = false,
    int maxLines = 1,
    TextInputType keyboardType = TextInputType.text,
  }) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          label,
          style: const TextStyle(
            color: AppTheme.black,
            fontSize: 14,
            fontWeight: FontWeight.w700,
          ),
        ),
        const SizedBox(height: 8),
        TextField(
          controller: controller,
          readOnly: readOnly,
          maxLines: maxLines,
          keyboardType: keyboardType,
          decoration: InputDecoration(
            prefixIcon: Icon(
              icon,
              color: AppTheme.gray,
              size: 22,
            ),
          ),
        ),
      ],
    );
  }
}