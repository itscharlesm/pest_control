import 'package:flutter/material.dart';
import 'signup_page.dart';
import 'forgot_password_page.dart';
import 'dashboard_page.dart';
import '../widgets/snackbars/login_snackbar.dart';
import 'dart:convert';
import 'package:http/http.dart' as http;

class LoginPage extends StatefulWidget {
  const LoginPage({super.key});

  @override
  State<LoginPage> createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
  
    final TextEditingController emailController = TextEditingController();
    final TextEditingController passwordController = TextEditingController();

    bool isLoading = false;
    bool isPasswordVisible = false;

    Widget build(BuildContext context) {

    Future<void> login() async {

      String email = emailController.text.trim();
      String password = passwordController.text;

      // EMPTY CHECK
      if (email.isEmpty || password.isEmpty) {
        showLoginSnackbar(
          context: context,
          message: "Please enter both email and password.",
        );
        return;
      }

      // EMAIL FORMAT CHECK
      if (!email.contains("@")) {
        showLoginSnackbar(
          context: context,
          message: "Please enter a valid email address.",
        );
        return;
      }

      // PASSWORD BASIC CHECK
      if (password.length < 6) {
        showLoginSnackbar(
          context: context,
          message: "Password must be at least 6 characters.",
        );
        return;
      }

      // ONLY REACH HERE IF VALID INPUT
      setState(() {
        isLoading = true;
      });

      // API CALL

      try {
        String email = emailController.text.trim();
        String password = passwordController.text;

        // ✅ HARD CODED LOGIN (FOR TESTING)
        if (email == "admin@gmail.com" && password == "123456") {
          Navigator.pushReplacement(
            context,
            MaterialPageRoute(
              builder: (_) => DashboardPage(email: email),
            ),
          );
          return; // stop here, skip API
        }

        // 🔥 ORIGINAL API LOGIN
        final response = await http.post(
          Uri.parse("http://10.0.2.2:8000/api/login"),
          headers: {"Content-Type": "application/json"},
          body: jsonEncode({
            "email": email,
            "password": password,
          }),
        );

        final data = jsonDecode(response.body);

        if (!mounted) return;

        if (response.statusCode == 200 && data['success'] == true) {
          Navigator.pushReplacement(
            context,
            MaterialPageRoute(
              builder: (_) => DashboardPage(
                email: data['user']['usr_email'],
              ),
            ),
          );
        } else {
          showLoginSnackbar(
            context: context,
            message: data['message'] ?? 'Login failed',
          );
        }
      } catch (e) {
        if (!mounted) return;
          showLoginSnackbar(
            context: context,
            message: "Error: $e",
          );
      } finally {
        if (mounted) {
          setState(() {
            isLoading = false;
          });
        }
      }
    }

    return GestureDetector(
      onTap: () => FocusScope.of(context).unfocus(),
      child: Scaffold(
        backgroundColor: Colors.white,
        resizeToAvoidBottomInset: true,
        body: SafeArea(
          child: SingleChildScrollView(
            padding: EdgeInsets.fromLTRB(
              24,
              0,
              24,
              MediaQuery.of(context).viewInsets.bottom + 24,
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const SizedBox(height: 10),

                // Back button
                IconButton(
                  onPressed: () {
                    Navigator.pop(context);
                  },
                  icon: const Icon(Icons.arrow_back_ios_new),
                ),

                const SizedBox(height: 20),

                // Title
                const Text(
                  "Welcome Back",
                  style: TextStyle(
                    fontSize: 26,
                    fontWeight: FontWeight.w800,
                  ),
                ),

                const SizedBox(height: 6),

                const Text(
                  "Login to your account",
                  style: TextStyle(
                    fontSize: 15,
                    color: Color(0xFF6B6B6B),
                  ),
                ),

                const SizedBox(height: 30),

                // Email
                TextField(
                  controller: emailController,
                  keyboardType: TextInputType.emailAddress,
                  decoration: const InputDecoration(
                    labelText: "Email",
                  ),
                ),

                const SizedBox(height: 16),

                // Password
                TextField(
                  controller: passwordController,
                  obscureText: !isPasswordVisible,
                  decoration: InputDecoration(
                    labelText: "Password",
                    suffixIcon: IconButton(
                      icon: Icon(
                        isPasswordVisible ? Icons.visibility : Icons.visibility_off,
                      ),
                      onPressed: () {
                        setState(() {
                          isPasswordVisible = !isPasswordVisible;
                        });
                      },
                    ),
                  ),
                ),

                const SizedBox(height: 10),

                // Forgot password
                Align(
                  alignment: Alignment.centerRight,
                  child: TextButton(
                    onPressed: () {
                    Navigator.push(
                        context,
                        MaterialPageRoute(
                        builder: (_) => const ForgotPasswordPage(),
                        ),
                    );
                    },
                    child: const Text("Forgot password?"),
                  ),
                ),

                const SizedBox(height: 20),

                // Login button
                SizedBox(
                  width: double.infinity,
                  height: 50,
                  child: ElevatedButton(
                    onPressed: isLoading ? null : login,
                    child: isLoading
                    ? const SizedBox(
                        width: 20,
                        height: 20,
                        child: CircularProgressIndicator(
                          strokeWidth: 2,
                          color: Colors.white,
                        ),
                      )
                    : const Text("Log in"),
                  ),
                ),

                const SizedBox(height: 20),

                // Sign up redirect
                Center(
                  child: GestureDetector(
                    onTap: () {
                    Navigator.push(
                        context,
                        MaterialPageRoute(
                        builder: (_) => const SignUpPage(),
                        ),
                    );
                    },
                    child: RichText(
                    text: TextSpan(
                        text: "Don't have an account? ",
                        style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                        color: Colors.black54,
                        fontSize: 14,
                        ),
                        children: [
                        TextSpan(
                            text: "Sign up",
                            style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                            color: Theme.of(context).colorScheme.primary,
                            fontWeight: FontWeight.bold,
                            fontSize: 14,
                            ),
                        ),
                        ],
                    ),
                    ),
                  ),
                ),

                const SizedBox(height: 40),
              ],
            ),
          ),
        ),
      ),
    );
  }
}