import 'dart:io';
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:mobile_app/config/api_config.dart';
import 'package:flutter/material.dart';
import 'package:mobile_app/app/theme.dart';
import 'package:mobile_app/shared/widgets/headers/app_back_header.dart';
import 'package:mobile_app/features/bookings/widgets/booking_step_indicator.dart';
import 'package:mobile_app/features/bookings/pages/client_booking_schedule_page.dart';
import 'package:image_picker/image_picker.dart';

  class ClientBookingProblemPage extends StatefulWidget {
    final String email;
    final Map<String, dynamic> selectedAddress;

    const ClientBookingProblemPage({
      super.key,
      required this.email,
      required this.selectedAddress,
    });

    @override
    State<ClientBookingProblemPage> createState() =>
        _ClientBookingProblemPageState();
  }

  class _ClientBookingProblemPageState extends State<ClientBookingProblemPage> {

    List<Map<String, dynamic>> selectedServicePackages = [];
    List<Map<String, dynamic>> selectedAreas = [];
    List<XFile> selectedImages = [];
    final ImagePicker _picker = ImagePicker();

    List<Map<String, dynamic>> servicePackages = [];
    bool isLoadingPackages = false;

    List<Map<String, dynamic>> serviceAreas = [];
    bool isLoadingAreas = false;

    final TextEditingController descriptionController =
        TextEditingController();

    int get totalPrice {
      int total = 0;

      for (final area in selectedAreas) {
        total += int.tryParse(area['cost'].toString()) ?? 0;
      }

      return total;
    }

    String _serviceImage(String name) {
      switch (name.toUpperCase()) {
        case 'ANTS':
          return 'assets/images/img_ant.png';
        case 'BED BUGS':
          return 'assets/images/img_bedbug.png';
        case 'COCKROACHES':
          return 'assets/images/img_cockroach.png';
        case 'FLIES':
          return 'assets/images/img_fly.png';
        case 'MOSQUITOES':
          return 'assets/images/img_mosquito.png';
        case 'RATS/MICE':
          return 'assets/images/img_rat.png';
        case 'SPIDERS':
          return 'assets/images/img_spider.png';
        case 'TERMITES':
          return 'assets/images/img_termites.png';
        case 'OTHERS':
          return 'assets/images/img_others.png';
        default:
          return 'assets/images/img_defaultcards.png';
      }
    }

    String _areaImage(String area) {
      switch (area.toUpperCase()) {
        case 'ATTIC':
          return 'assets/images/img_attic.png';

        case 'BASEMENT':
          return 'assets/images/img_basement.png';

        case 'BATHROOM':
          return 'assets/images/img_bathroom.png';

        case 'BEDROOM':
          return 'assets/images/img_bedroom.png';

        case 'DINING ROOM':
          return 'assets/images/img_diningroom.png';

        case 'GARAGE':
          return 'assets/images/img_garage.png';

        case 'GARDEN/YARD':
          return 'assets/images/img_gardenyard.png';

        case 'KITCHEN':
          return 'assets/images/img_kitchen.png';

        case 'LIVING ROOM':
          return 'assets/images/img_livingroom.png';

        case 'OFFICE/STUDY':
          return 'assets/images/img_officestudy.png';

        case 'STORAGE ROOM':
          return 'assets/images/img_storageroom.png';

        case 'WHOLE PROPERTY':
          return 'assets/images/img_wholeproperty.png';

        default:
          return 'assets/images/img_defaultcards.png';
      }
    }

    String _serviceShortText(String name) {
      switch (name.toUpperCase()) {
        case 'ANTS':
          return 'Small ants found indoors or outdoors.';
        case 'BED BUGS':
          return 'Bugs usually found in beds and furniture.';
        case 'COCKROACHES':
          return 'Common pests in kitchens and bathrooms.';
        case 'FLIES':
          return 'Flies around food, waste, or damp areas.';
        case 'MOSQUITOES':
          return 'Biting insects common in open areas.';
        case 'RATS/MICE':
          return 'Rodents found in rooms or storage areas.';
        case 'SPIDERS':
          return 'Webs or spiders seen around the property.';
        case 'TERMITES':
          return 'Wood-damaging pests in walls or furniture.';
        case 'OTHERS':
          return 'Other pest issues not listed here.';
        default:
          return 'Pest control service option.';
      }
    }

    String _formatServiceName(String text) {
      return text
          .toLowerCase()
          .split(' ')
          .map((word) {
            if (word.isEmpty) return word;

            return word[0].toUpperCase() + word.substring(1);
          })
          .join(' ');
    }

    String _formatAreaName(String text) {
      return text
          .toLowerCase()
          .split(' ')
          .map((word) {
            if (word.isEmpty) return word;

            return word[0].toUpperCase() + word.substring(1);
          })
          .join(' ');
    }

    @override
    void initState() {
      super.initState();
      _loadServicePackages();
      _loadServiceAreas(2);

      descriptionController.addListener(() {
        setState(() {});
      });
    }

    @override
    Widget build(BuildContext context) {
      return Scaffold(
        backgroundColor: AppTheme.lightGray,
        appBar: const AppBackHeader(
          title: 'Book Service',
        ),
        body: Column(
          children: [
            BookingStepIndicator(currentStep: 2),
            Expanded(
              child: SingleChildScrollView(
                padding: const EdgeInsets.all(20),
                child: Column(
                  children: [
                    _problemCard(),
                    const SizedBox(height: 20),
                    _areasCard(),
                    const SizedBox(height: 20),
                    _descriptionCard(),
                    const SizedBox(height: 20),
                    _photoCard(),
                  ],
                ),
              ),
            ),
            _bottomBar(),
          ],
        ),
      );
    }

    Widget _problemCard() {
      return Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            "What’s the pest problem you have?",
            style: TextStyle(
              fontSize: 16,
              fontWeight: FontWeight.bold,
              color: AppTheme.black,
            ),
          ),
          const SizedBox(height: 5),
          const Text(
            'Select the type of pest you’re dealing with.',
            style: TextStyle(
              color: AppTheme.gray,
              fontSize: 12,
            ),
          ),
          const SizedBox(height: 14),

          if (isLoadingPackages)
            const Center(
              child: Padding(
                padding: EdgeInsets.all(24),
                child: CircularProgressIndicator(
                  color: Color.fromARGB(255, 45, 255, 26),
                ),
              ),
            )
          else
            SizedBox(
              height: 220,
              child: ListView.separated(
                scrollDirection: Axis.horizontal,
                itemCount: servicePackages.length,
                separatorBuilder: (_, __) => const SizedBox(width: 10),
                itemBuilder: (context, index) {
                  final service = servicePackages[index];
                  final isSelected = selectedServicePackages.any(
                    (selected) => selected['id'] == service['id'],
                  );

                  return GestureDetector(
                    onTap: () {
                      setState(() {

                        if (isSelected) {
                          selectedServicePackages.removeWhere(
                            (selected) => selected['id'] == service['id'],
                          );
                        } else {
                          selectedServicePackages.add(service);
                        }

                      });
                    },
                    child: Container(
                      width: 130,
                      padding: const EdgeInsets.all(10),
                      decoration: BoxDecoration(
                        color: isSelected
                            ? AppTheme.primaryRed
                            : AppTheme.white,
                        borderRadius: BorderRadius.circular(10),
                        border: Border.all(
                          color: isSelected
                              ? AppTheme.primaryRed
                              : AppTheme.borderGray,
                          width: isSelected ? 1.3 : 1,
                        ),
                      ),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [

                          Container(
                            height: 110,
                            width: double.infinity,
                            decoration: BoxDecoration(
                              color: AppTheme.white,
                              borderRadius: BorderRadius.circular(8),
                            ),
                            child: Padding(
                              padding: const EdgeInsets.all(4),
                              child: Image.asset(
                                _serviceImage(service['name'] ?? ''),
                                fit: BoxFit.contain,
                              ),
                            ),
                          ),

                          const SizedBox(height: 12),

                          Text(
                            _formatServiceName(service['name'] ?? ''),
                            maxLines: 3,
                            overflow: TextOverflow.ellipsis,
                            style: TextStyle(
                              color: isSelected
                                  ? AppTheme.white
                                  : AppTheme.black,
                              fontSize: 15,
                              fontWeight: FontWeight.bold,
                              height: 1.15,
                            ),
                          ),

                          const SizedBox(height: 6),

                          Text(
                            _serviceShortText(service['name'] ?? ''),
                            maxLines: 3,
                            overflow: TextOverflow.ellipsis,
                            style: TextStyle(
                              color: isSelected
                                  ? AppTheme.white
                                  : AppTheme.gray,
                              fontSize: 12,
                              height: 1.25,
                            ),
                          ),
                        ],
                      ),
                    ),
                  );
                },
              ),
            ),

            if (selectedServicePackages.isNotEmpty) ...[
              const SizedBox(height: 12),

              Container(
                width: double.infinity,
                padding: const EdgeInsets.symmetric(
                  horizontal: 10,
                  vertical: 9,
                ),
                decoration: BoxDecoration(
                  color: AppTheme.white,
                  borderRadius: BorderRadius.circular(9),
                  border: Border.all(
                    color: AppTheme.primaryRed.withOpacity(0.15),
                  ),
                ),
                child: Row(
                  children: [
                    const Icon(
                      Icons.check_circle,
                      color: AppTheme.primaryRed,
                      size: 16,
                    ),

                    const SizedBox(width: 7),

                    Expanded(
                      child: Text(
                        '${selectedServicePackages.length} pest types selected',
                        style: const TextStyle(
                          color: AppTheme.primaryRed,
                          fontSize: 12,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),

                    GestureDetector(
                      onTap: () {
                        setState(() {
                          selectedServicePackages.clear();
                        });
                      },
                      child: const Text(
                        'Clear all',
                        style: TextStyle(
                          color: AppTheme.primaryRed,
                          fontSize: 12,
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            ],
        ],
      );
    }

    Widget _areasCard() {
      return Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            'Where did the problem occur?',
            style: TextStyle(
              fontSize: 16,
              fontWeight: FontWeight.bold,
              color: AppTheme.black,
            ),
          ),
          const SizedBox(height: 5),
          const Text(
            'Select all areas where the pest problem is present.',
            style: TextStyle(
              color: AppTheme.gray,
              fontSize: 12,
            ),
          ),
          const SizedBox(height: 14),

          SizedBox(
            height: 220,
            child: ListView.separated(
              padding: const EdgeInsets.only(right: 20),
              scrollDirection: Axis.horizontal,
              itemCount: serviceAreas.length,
              separatorBuilder: (_, __) => const SizedBox(width: 10),
              itemBuilder: (context, index) {
                final area = serviceAreas[index];

                final isSelected = selectedAreas.any(
                  (selected) => selected['id'] == area['id'],
                );

                return GestureDetector(
                  onTap: () {
                    setState(() {
                      if (isSelected) {
                        selectedAreas.removeWhere(
                          (selected) => selected['id'] == area['id'],
                        );
                      } else {
                        selectedAreas.add(area);
                      }
                    });
                  },
                  child: Container(
                    width: 130,
                    padding: const EdgeInsets.all(10),
                    decoration: BoxDecoration(
                      color: isSelected ? AppTheme.primaryRed : AppTheme.white,
                      borderRadius: BorderRadius.circular(10),
                      border: Border.all(
                        color: isSelected ? AppTheme.primaryRed : AppTheme.borderGray,
                        width: isSelected ? 1.3 : 1,
                      ),
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Container(
                          height: 110,
                          width: double.infinity,
                          decoration: BoxDecoration(
                            color: AppTheme.white,
                            borderRadius: BorderRadius.circular(8),
                          ),
                          child: Padding(
                            padding: const EdgeInsets.all(4),
                            child: Center(
                              child: SizedBox(
                                width: 82,
                                height: 82,
                                child: Image.asset(
                                  _areaImage(area['area'] ?? ''),
                                  fit: BoxFit.contain,
                                ),
                              ),
                            ),
                          ),
                        ),

                        const SizedBox(height: 12),

                        Text(
                          _formatAreaName(area['area'] ?? ''),
                          maxLines: 2,
                          overflow: TextOverflow.ellipsis,
                          style: TextStyle(
                            color: isSelected ? AppTheme.white : AppTheme.black,
                            fontSize: 15,
                            fontWeight: FontWeight.bold,
                            height: 1.15,
                          ),
                        ),

                        const SizedBox(height: 6),

                        Text(
                          '₱${area['cost']}',
                          style: TextStyle(
                            color: isSelected ? AppTheme.white : AppTheme.gray,
                            fontSize: 12,
                            fontWeight: FontWeight.w600,
                          ),
                        ),
                      ],
                    ),
                  ),
                );
              },
            ),
          ),

          if (selectedAreas.isNotEmpty) ...[
            const SizedBox(height: 12),
            Container(
              width: double.infinity,
              padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 9),
              decoration: BoxDecoration(
                color: AppTheme.white,
                borderRadius: BorderRadius.circular(9),
                border: Border.all(
                  color: AppTheme.primaryRed.withOpacity(0.15),
                ),
              ),
              child: Row(
                children: [
                  const Icon(
                    Icons.check_circle,
                    color: AppTheme.primaryRed,
                    size: 16,
                  ),
                  const SizedBox(width: 7),
                  Expanded(
                    child: Text(
                      '${selectedAreas.length} areas selected',
                      style: const TextStyle(
                        color: AppTheme.primaryRed,
                        fontSize: 12,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
                  GestureDetector(
                    onTap: () {
                      setState(() {
                        selectedAreas.clear();
                      });
                    },
                    child: const Text(
                      'Clear all',
                      style: TextStyle(
                        color: AppTheme.primaryRed,
                        fontSize: 12,
                        fontWeight: FontWeight.w600,
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ],
        ],
      );
    }

    Widget _descriptionCard() {
      return Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Row(
            children: [
              Text(
                'Describe the problem',
                style: TextStyle(
                  color: AppTheme.black,
                  fontSize: 16,
                  fontWeight: FontWeight.bold,
                ),
              ),
              SizedBox(width: 4),
              Text(
                '(optional)',
                style: TextStyle(
                  color: AppTheme.gray,
                  fontSize: 13,
                ),
              ),
            ],
          ),

          const SizedBox(height: 6),

          const Text(
            'Tell us more about the pest problem you’re experiencing.',
            style: TextStyle(
              color: AppTheme.gray,
              fontSize: 13,
              height: 1.3,
            ),
          ),

          const SizedBox(height: 12),

          Stack(
            children: [
              TextField(
                controller: descriptionController,
                textAlignVertical: TextAlignVertical.top,
                maxLines: 4,
                maxLength: 500,
                decoration: InputDecoration(
                  hintText: 'Type your problem here...',
                  hintStyle: const TextStyle(
                    color: AppTheme.gray,
                    fontSize: 12,
                  ),
                  counterText: '',
                  filled: true,
                  fillColor: AppTheme.white,
                  contentPadding: const EdgeInsets.fromLTRB(14, 14, 14, 34),
                  enabledBorder: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(12),
                    borderSide: const BorderSide(color: AppTheme.borderGray),
                  ),
                  focusedBorder: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(12),
                    borderSide: const BorderSide(color: AppTheme.primaryRed),
                  ),
                ),
              ),

              Positioned(
                right: 12,
                bottom: 10,
                child: Text(
                  '${descriptionController.text.length}/500',
                  style: const TextStyle(
                    color: AppTheme.gray,
                    fontSize: 11,
                  ),
                ),
              ),
            ],
          ),
        ],
      );
    }

    Widget _photoCard() {
      return Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Row(
            children: [
              Text(
                'Add photos',
                style: TextStyle(
                  color: AppTheme.black,
                  fontSize: 16,
                  fontWeight: FontWeight.bold,
                ),
              ),
              SizedBox(width: 4),
              Text(
                '(optional)',
                style: TextStyle(
                  color: AppTheme.gray,
                  fontSize: 13,
                ),
              ),
            ],
          ),

          const SizedBox(height: 6),

          const Text(
            'Attach clear photos to help us understand the problem better.',
            style: TextStyle(
              color: AppTheme.gray,
              fontSize: 13,
              height: 1.3,
            ),
          ),

          const SizedBox(height: 14),

          SizedBox(
            height: 98,
            child: ListView.separated(
              scrollDirection: Axis.horizontal,
              itemCount: selectedImages.length + 1,
              separatorBuilder: (_, __) => const SizedBox(width: 12),
              itemBuilder: (context, index) {

                // ADD PHOTO CARD
                if (index == 0) {
                  return GestureDetector(
                    onTap: _pickImages,
                    child: Container(
                    width: 110,
                    decoration: BoxDecoration(
                      color: AppTheme.white,
                      borderRadius: BorderRadius.circular(14),
                      border: Border.all(
                        color: AppTheme.primaryRed.withOpacity(0.35),
                      ),
                    ),
                      child: const Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Icon(
                          Icons.camera_alt,
                          color: AppTheme.primaryRed,
                          size: 30,
                        ),

                        SizedBox(height: 8),

                        Text(
                          'Add Photo',
                          style: TextStyle(
                            color: AppTheme.primaryRed,
                            fontSize: 13,
                            fontWeight: FontWeight.w600,
                          ),
                        ),
                      ],
                    ),
                    ),
                  );
                }

                final image = selectedImages[index - 1];

                return Stack(
                  children: [
                    Container(
                      width: 112,
                      decoration: BoxDecoration(
                        borderRadius: BorderRadius.circular(14),
                        image: DecorationImage(
                          image: FileImage(
                            File(image.path),
                          ),
                          fit: BoxFit.cover,
                        ),
                      ),
                    ),

                    Positioned(
                      top: 8,
                      right: 8,
                      child: GestureDetector(
                        onTap: () {
                          setState(() {
                            selectedImages.removeAt(index - 1);
                          });
                        },
                        child: Container(
                          width: 24,
                          height: 24,
                          decoration: const BoxDecoration(
                            color: AppTheme.white,
                            shape: BoxShape.circle,
                          ),
                          child: const Icon(
                            Icons.close,
                            size: 16,
                            color: AppTheme.black,
                          ),
                        ),
                      ),
                    ),
                  ],
                );
              },
            ),
          ),

          const SizedBox(height: 10),

          Row(
            children: [
              Icon(
                Icons.info_outline,
                size: 14,
                color: AppTheme.gray.withOpacity(0.9),
              ),

              const SizedBox(width: 5),

              Text(
                'You can add up to 10 photos',
                style: TextStyle(
                  color: AppTheme.gray.withOpacity(0.9),
                  fontSize: 12,
                ),
              ),
            ],
          ),
        ],
      );
    }

    Widget _bottomBar() {
      return Container(
        padding: const EdgeInsets.all(16),
        color: AppTheme.white,
        child: ElevatedButton(
          onPressed: (selectedServicePackages.isNotEmpty && selectedAreas.isNotEmpty)
              ? () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (_) => ClientBookingSchedulePage(
                        email: widget.email,
                        selectedAddress: widget.selectedAddress,
                        selectedServicePackages: selectedServicePackages,
                        selectedAreas: selectedAreas,
                        description: descriptionController.text.trim(),
                        selectedImages: selectedImages,
                      ),
                    ),
                  );
                }
              : null,
          child: const Text('Continue'),
        ),
      );
    }

    Future<void> _loadServicePackages() async {
      setState(() {
        isLoadingPackages = true;
      });

      try {
        final response = await http.get(
          Uri.parse('${ApiConfig.baseUrl}/api/mobile/service-packages'),
          headers: {
            'Accept': 'application/json',
          },
        );

        final data = jsonDecode(response.body);

        if (data['success'] == true) {
          final List packageData = data['data'] ?? [];

          setState(() {
            servicePackages = packageData.map((item) {
              return {
                'id': item['svcp_id'],
                'name': item['svcp_pest_type'],
              };
            }).toList();

            servicePackages.sort((a, b) {
              if (a['name'] == 'OTHERS') return 1;
              if (b['name'] == 'OTHERS') return -1;
              return 0;
            });
          });
        }
      } catch (e) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Unable to load service packages.'),
          ),
        );
      } finally {
        if (mounted) {
          setState(() {
            isLoadingPackages = false;
          });
        }
      }
    }

    Future<void> _loadServiceAreas(int branchId) async {
      setState(() {
        isLoadingAreas = true;
      });

      try {
        final response = await http.get(
          Uri.parse('${ApiConfig.baseUrl}/api/mobile/service-package-areas/$branchId'),
          headers: {
            'Accept': 'application/json',
          },
        );

        final data = jsonDecode(response.body);

        if (data['success'] == true) {
          final List areaData = data['data'] ?? [];

          setState(() {
            serviceAreas = areaData.map((item) {
              return {
                'id': item['svcpa_id'],
                'area': item['svcpa_area'],
                'cost': item['svcpa_cost'],
              };
            }).toList();
          });
        }
      } catch (e) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Unable to load service areas.')),
        );
      } finally {
        if (mounted) {
          setState(() {
            isLoadingAreas = false;
          });
        }
      }
    }

    Future<void> _pickImages() async {
      if (selectedImages.length >= 10) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Maximum of 10 photos only.'),
          ),
        );
        return;
      }

      final List<XFile> images = await _picker.pickMultiImage();

      if (images.isNotEmpty) {
        setState(() {
          final remaining = 10 - selectedImages.length;

          selectedImages.addAll(
            images.take(remaining),
          );
        });
      }
    }
  }