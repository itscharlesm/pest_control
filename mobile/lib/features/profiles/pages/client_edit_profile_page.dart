import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:mobile_app/app/theme.dart';
import 'package:mobile_app/shared/shared.dart';
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:mobile_app/config/api_config.dart';

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

  bool isSaving = false;
  bool isFetching = true;
  final TextEditingController firstNameController = TextEditingController();

  final TextEditingController middleNameController = TextEditingController();

  final TextEditingController lastNameController = TextEditingController();

  final TextEditingController phoneController = TextEditingController();

  final TextEditingController birthDateController = TextEditingController();

  final TextEditingController emailController = TextEditingController();

  DateTime? selectedDate;

  String formatName(String value) {
    if (value.isEmpty) return '';
    return value
        .toLowerCase()
        .split(' ')
        .map((word) =>
            word.isNotEmpty
                ? word[0].toUpperCase() + word.substring(1)
                : '')
        .join(' ');
  }

  String formatPhone(String value) {
    if (value.isEmpty) return '';

    // remove spaces just in case
    value = value.replaceAll(' ', '');

    if (!value.startsWith('0')) {
      return '0$value';
    }

    return value;
  }

  @override
  void initState() {
    super.initState();
    emailController.text = widget.email;
    _fetchUserProfile();
  }

  @override
  void dispose() {
    firstNameController.dispose();
    middleNameController.dispose();
    lastNameController.dispose();
    phoneController.dispose();
    emailController.dispose();
    birthDateController.dispose();
    super.dispose();
  }

  Future<void> _fetchUserProfile() async {
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
          firstNameController.text =
              formatName(user['usr_first_name'] ?? '');

          middleNameController.text =
              formatName(user['usr_middle_name'] ?? '');

          lastNameController.text =
              formatName(user['usr_last_name'] ?? '');

          phoneController.text = formatPhone(user['usr_mobile'] ?? '');
          birthDateController.text = user['usr_birth_date'] ?? '';
          emailController.text = user['usr_email'] ?? widget.email;
          isFetching = false;
        });
      } else {
        setState(() => isFetching = false);
      }
    } catch (e) {
      if (!mounted) return;
      setState(() => isFetching = false);

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Unable to load profile: $e'),
        ),
      );
    }
  }

  Future<void> _pickDate() async {
    DateTime initialDate = DateTime.now().subtract(const Duration(days: 3650));

    final DateTime? picked = await showDatePicker(
      context: context,
      initialDate: initialDate,
      firstDate: DateTime(1950),
      lastDate: DateTime.now(),
    );

    if (picked != null) {
      setState(() {
        selectedDate = picked;
        birthDateController.text = DateFormat('yyyy-MM-dd').format(picked);
      });
    }
  }

  Future<void> _saveProfile() async {
    if (firstNameController.text.trim().isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("First name is required.")),
      );
      return;
    }

    if (lastNameController.text.trim().isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("Last name is required.")),
      );
      return;
    }

    setState(() => isSaving = true);

    try {
      final response = await http.post(
        Uri.parse('${ApiConfig.baseUrl}/api/mobile/profile/update'),
        headers: {'Accept': 'application/json'},
        body: {
          'email': emailController.text.trim(),
          'first_name': firstNameController.text.trim(),
          'middle_name': middleNameController.text.trim(),
          'last_name': lastNameController.text.trim(),
          'birth_date': birthDateController.text.trim(),
        },
      );

      final data = jsonDecode(response.body);

      if (!mounted) return;

      if (response.statusCode == 200 && data['success'] == true) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text("Profile updated successfully.")),
        );

        Navigator.pop(context);
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(data['message'] ?? "Failed to update profile.")),
        );
      }
    } catch (e) {
      if (!mounted) return;

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text("Unable to update profile: $e")),
      );
    } finally {
      if (mounted) {
        setState(() => isSaving = false);
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: const AppBackHeader(
        title: "Edit Profile",
      ),
      body: isFetching
      ? const Center(child: CircularProgressIndicator())
      : SingleChildScrollView(
        padding: const EdgeInsets.fromLTRB(20, 22, 20, 30),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // 🔴 PROFILE IMAGE
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

            // NAME FIELDS
            _input("First Name *", firstNameController, Icons.person_outline),
            const SizedBox(height: 16),

            _input("Middle Name", middleNameController, Icons.person_outline),
            const SizedBox(height: 16),

            _input("Last Name *", lastNameController, Icons.person_outline),
            const SizedBox(height: 16),

            // EMAIL (READ ONLY)
            _input("Email", emailController, Icons.email_outlined,
                readOnly: true), //readonly kay need api
            const SizedBox(height: 16),

            // PHONE
            _input(
              "Mobile Number",
              phoneController,
              Icons.phone_outlined,
              readOnly: true, //readonly sani kay need ni ug api for verification
            ),
            const SizedBox(height: 16),

            // BIRTHDATE
            GestureDetector(
              onTap: _pickDate,
              child: AbsorbPointer(
                child: _input(
                  "Birth Date",
                  birthDateController,
                  Icons.calendar_month_outlined,
                ),
              ),
            ),

            const SizedBox(height: 24),

            // INFO BOX
            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: AppTheme.primaryRed.withOpacity(0.06),
                borderRadius: BorderRadius.circular(14),
              ),
              child: Row(
                children: const [
                  Icon(Icons.shield_outlined, color: AppTheme.primaryRed),
                  SizedBox(width: 10),
                  Expanded(
                    child: Text(
                      "Your information is secure and used only for service purposes.",
                      style: TextStyle(fontSize: 13),
                    ),
                  ),
                ],
              ),
            ),

            const SizedBox(height: 28),

            // SAVE BUTTON
            LoadingButton(
              isLoading: isSaving,
              onPressed: isSaving ? null : _saveProfile,
              child: const Text("Save Changes"),
            ),
          ],
        ),
      ),
    );
  }

  Widget _input(String label, TextEditingController controller, IconData icon,
      {bool readOnly = false, TextInputType keyboard = TextInputType.text}) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          label,
          style: const TextStyle(
            fontWeight: FontWeight.w600,
            fontSize: 14,
          ),
        ),
        const SizedBox(height: 6),
        TextField(
          controller: controller,
          readOnly: readOnly,
          keyboardType: keyboard,
          decoration: InputDecoration(
            prefixIcon: Icon(icon, color: AppTheme.gray),
          ),
        ),
      ],
    );
  }
}