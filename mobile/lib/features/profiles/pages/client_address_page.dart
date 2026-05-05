import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:mobile_app/app/theme.dart';
import 'package:mobile_app/config/api_config.dart';
import 'package:mobile_app/shared/widgets/headers/app_back_header.dart';

class ClientAddressPage extends StatefulWidget {
  final String email;

  const ClientAddressPage({
    super.key,
    required this.email,
  });

  @override
  State<ClientAddressPage> createState() => _ClientAddressPageState();
}

class _ClientAddressPageState extends State<ClientAddressPage> {
  bool isLoadingRegions = true;
  bool isSaving = false;

  int selectedAddressTypeId = 1;
  int? selectedRegionId;
  int? selectedProvinceId;
  int? selectedMunicipalityId;
  int? selectedBarangayId;

  final List<Map<String, dynamic>> addressTypes = [
    {'id': 1, 'name': 'HOME'},
    {'id': 2, 'name': 'WORK'},
    {'id': 3, 'name': 'COMPANY'},
    {'id': 4, 'name': 'FAVORITE'},
    {'id': 5, 'name': 'RESIDENTIAL'},
  ];

  List<dynamic> regions = [];
  List<dynamic> provinces = [];
  List<dynamic> municipalities = [];
  List<dynamic> barangays = [];

  final TextEditingController streetController = TextEditingController();

  @override
  void initState() {
    super.initState();
    _fetchRegions();
  }

  @override
  void dispose() {
    streetController.dispose();
    super.dispose();
  }

  Future<void> _fetchRegions() async {
    try {
      final response = await http.get(
        Uri.parse('${ApiConfig.baseUrl}/api/mobile/location/regions'),
        headers: {'Accept': 'application/json'},
      );

      final data = jsonDecode(response.body);

      if (!mounted) return;

      if (response.statusCode == 200 && data['success'] == true) {
        setState(() {
          regions = data['data'];
          isLoadingRegions = false;
        });
      } else {
        _showMessage('Unable to load regions.');
        setState(() => isLoadingRegions = false);
      }
    } catch (_) {
      if (!mounted) return;
      _showMessage('Connection error while loading regions.');
      setState(() => isLoadingRegions = false);
    }
  }

  Future<void> _fetchProvinces(int regId) async {
    try {
      final response = await http.get(
        Uri.parse('${ApiConfig.baseUrl}/api/mobile/location/provinces/$regId'),
        headers: {'Accept': 'application/json'},
      );

      final data = jsonDecode(response.body);

      if (!mounted) return;

      if (response.statusCode == 200 && data['success'] == true) {
        setState(() {
          provinces = data['data'];
        });
      } else {
        _showMessage('Unable to load provinces.');
      }
    } catch (_) {
      _showMessage('Connection error while loading provinces.');
    }
  }

  Future<void> _fetchMunicipalities(int provId) async {
    try {
      final response = await http.get(
        Uri.parse(
          '${ApiConfig.baseUrl}/api/mobile/location/municipalities/$provId',
        ),
        headers: {'Accept': 'application/json'},
      );

      final data = jsonDecode(response.body);

      if (!mounted) return;

      if (response.statusCode == 200 && data['success'] == true) {
        setState(() {
          municipalities = data['data'];
        });
      } else {
        _showMessage('Unable to load municipalities.');
      }
    } catch (_) {
      _showMessage('Connection error while loading municipalities.');
    }
  }

  Future<void> _fetchBarangays(int munId) async {
    try {
      final response = await http.get(
        Uri.parse('${ApiConfig.baseUrl}/api/mobile/location/barangays/$munId'),
        headers: {'Accept': 'application/json'},
      );

      final data = jsonDecode(response.body);

      if (!mounted) return;

      if (response.statusCode == 200 && data['success'] == true) {
        setState(() {
          barangays = data['data'];
        });
      } else {
        _showMessage('Unable to load barangays.');
      }
    } catch (_) {
      _showMessage('Connection error while loading barangays.');
    }
  }

  void _saveAddress() {
    if (selectedRegionId == null ||
        selectedProvinceId == null ||
        selectedMunicipalityId == null ||
        selectedBarangayId == null ||
        streetController.text.trim().isEmpty) {
      _showMessage('Please complete all address fields.');
      return;
    }

    setState(() {
      isSaving = true;
    });

    Future.delayed(const Duration(milliseconds: 800), () {
      if (!mounted) return;

      setState(() {
        isSaving = false;
      });

      _showMessage('Address save API will be connected next.');
    });
  }

  void _showMessage(String message) {
    if (!mounted) return;

    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text(message)),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.lightGray,
      appBar: const AppBackHeader(
        title: 'My Address',
      ),
      body: isLoadingRegions
          ? const Center(
              child: CircularProgressIndicator(
                color: AppTheme.primaryRed,
              ),
            )
          : SingleChildScrollView(
              padding: const EdgeInsets.fromLTRB(20, 18, 20, 24),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  _infoCard(),
                  const SizedBox(height: 18),
                  _addressFormCard(),
                ],
              ),
            ),
    );
  }

  Widget _infoCard() {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(16),
      decoration: AppTheme.borderedCardDecoration,
      child: const Text(
        'Set up your service address first. This address will be used when booking pest control appointments.',
        style: TextStyle(
          color: AppTheme.gray,
          fontSize: 14,
          height: 1.4,
        ),
      ),
    );
  }

  Widget _addressFormCard() {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(18),
      decoration: AppTheme.borderedCardDecoration,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            'Address Details',
            style: TextStyle(
              color: AppTheme.black,
              fontSize: 18,
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 18),

          _addressTypeDropdown(),
          const SizedBox(height: 14),

          _regionDropdown(),
          const SizedBox(height: 14),

          _provinceDropdown(),
          const SizedBox(height: 14),

          _municipalityDropdown(),
          const SizedBox(height: 14),

          _barangayDropdown(),
          const SizedBox(height: 14),

          _streetField(),
          const SizedBox(height: 22),

          LoadingButton(
            isLoading: isSaving,
            onPressed: _saveAddress,
            child: const Text('Save Address'),
          ),
        ],
      ),
    );
  }

  Widget _addressTypeDropdown() {
    return DropdownButtonFormField<int>(
      value: selectedAddressTypeId,
      decoration: const InputDecoration(
        labelText: 'Address Type',
      ),
      items: addressTypes.map((type) {
        return DropdownMenuItem<int>(
          value: type['id'],
          child: Text(type['name']),
        );
      }).toList(),
      onChanged: (value) {
        if (value == null) return;

        setState(() {
          selectedAddressTypeId = value;
        });
      },
    );
  }

  Widget _regionDropdown() {
    return DropdownButtonFormField<int>(
      value: selectedRegionId,
      decoration: const InputDecoration(
        labelText: 'Region',
      ),
      items: regions.map((region) {
        return DropdownMenuItem<int>(
          value: region['reg_id'],
          child: Text(region['reg_name']),
        );
      }).toList(),
      onChanged: (value) {
        if (value == null) return;

        setState(() {
          selectedRegionId = value;
          selectedProvinceId = null;
          selectedMunicipalityId = null;
          selectedBarangayId = null;
          provinces = [];
          municipalities = [];
          barangays = [];
        });

        _fetchProvinces(value);
      },
    );
  }

  Widget _provinceDropdown() {
    return DropdownButtonFormField<int>(
      value: selectedProvinceId,
      decoration: const InputDecoration(
        labelText: 'Province',
      ),
      items: provinces.map((province) {
        return DropdownMenuItem<int>(
          value: province['prov_id'],
          child: Text(province['prov_name']),
        );
      }).toList(),
      onChanged: selectedRegionId == null
          ? null
          : (value) {
              if (value == null) return;

              setState(() {
                selectedProvinceId = value;
                selectedMunicipalityId = null;
                selectedBarangayId = null;
                municipalities = [];
                barangays = [];
              });

              _fetchMunicipalities(value);
            },
    );
  }

  Widget _municipalityDropdown() {
    return DropdownButtonFormField<int>(
      value: selectedMunicipalityId,
      decoration: const InputDecoration(
        labelText: 'City / Municipality',
      ),
      items: municipalities.map((municipality) {
        return DropdownMenuItem<int>(
          value: municipality['mun_id'],
          child: Text(municipality['mun_name']),
        );
      }).toList(),
      onChanged: selectedProvinceId == null
          ? null
          : (value) {
              if (value == null) return;

              setState(() {
                selectedMunicipalityId = value;
                selectedBarangayId = null;
                barangays = [];
              });

              _fetchBarangays(value);
            },
    );
  }

  Widget _barangayDropdown() {
    return DropdownButtonFormField<int>(
      value: selectedBarangayId,
      decoration: const InputDecoration(
        labelText: 'Barangay',
      ),
      items: barangays.map((barangay) {
        return DropdownMenuItem<int>(
          value: barangay['brg_id'],
          child: Text(barangay['brg_name']),
        );
      }).toList(),
      onChanged: selectedMunicipalityId == null
          ? null
          : (value) {
              if (value == null) return;

              setState(() {
                selectedBarangayId = value;
              });
            },
    );
  }

  Widget _streetField() {
    return TextField(
      controller: streetController,
      textCapitalization: TextCapitalization.words,
      decoration: const InputDecoration(
        labelText: 'Street / House No. / Building',
      ),
    );
  }
}