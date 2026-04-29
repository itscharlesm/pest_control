import 'package:flutter/material.dart';
import 'package:mobile_app/app/theme.dart';

class AppHomeHeader extends StatelessWidget implements PreferredSizeWidget {
  final String name;

  const AppHomeHeader({
    super.key,
    required this.name,
  });

  @override
  Size get preferredSize => const Size.fromHeight(60);

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: AppTheme.headerDecoration,
      child: SafeArea(
        child: Padding(
          padding: const EdgeInsets.symmetric(horizontal: 16),
          child: SizedBox(
            height: 60,
            child: Row(
              children: [
                const CircleAvatar(
                  radius: 16,
                  backgroundColor: AppTheme.primaryRed,
                  child: Icon(
                    Icons.person,
                    color: Colors.white,
                    size: 18,
                  ),
                ),
                const SizedBox(width: 10),
                Expanded(
                  child: Text(
                    name,
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                    style: const TextStyle(
                      color: AppTheme.primaryRed,
                      fontSize: 16,
                      fontWeight: FontWeight.w700,
                    ),
                  ),
                ),
                IconButton(
                  icon: const Icon(
                    Icons.menu_rounded,
                    color: AppTheme.primaryRed,
                    size: 30,
                  ),
                  onPressed: () => Scaffold.of(context).openDrawer(),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}