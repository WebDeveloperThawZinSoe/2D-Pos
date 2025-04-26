import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:html/parser.dart' show parse;
import 'package:url_launcher/url_launcher.dart';

class ShopDetailScreen extends StatefulWidget {
  final int shopId;

  ShopDetailScreen({required this.shopId});

  @override
  _ShopDetailScreenState createState() => _ShopDetailScreenState();
}

class _ShopDetailScreenState extends State<ShopDetailScreen> {
  Map<String, dynamic>? shop;
  List<dynamic> socials = [];
  List<dynamic> shopGallery = [];
  List<dynamic> products = [];
  List<dynamic> ads = [];
  bool isLoading = true;

  @override
  void initState() {
    super.initState();
    fetchShopDetails();
    fetchAds();
  }



  Future<void> fetchAds() async {
    final response = await http.get(
        Uri.parse('https://cryptodroplists.com/api/ads'));
    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      setState(() {
        ads = data['data'].where((ad) => ad['type'] == 'content_center').toList();
      });
    }
  }




  @override
  Widget build(BuildContext context) {
    double? latitude = double.tryParse(shop?['latitude']?.toString() ?? '');
    double? longitude = double.tryParse(shop?['longitude']?.toString() ?? '');
    return Scaffold(
      appBar: AppBar(
        title: Text(shop?['name'] ?? "Loading..."),
      ),
      body: isLoading
          ? Center(child: CircularProgressIndicator())
          : SingleChildScrollView(
        child: Padding(
          padding: const EdgeInsets.all(16.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
            

              // Ads (Content Center)
              if (ads.isNotEmpty)
                Column(
                  children: ads.map((ad) {
                    return Padding(
                      padding: const EdgeInsets.symmetric(vertical: 10.0),
                      child: ClipRRect(
                        borderRadius: BorderRadius.circular(12),
                        child: Image.network(
                          'https://cryptodroplists.com/storage/' + ad['image'],
                          width: double.infinity,
                          height: 70,
                          fit: BoxFit.cover,
                          loadingBuilder: (context, child, loadingProgress) {
                            if (loadingProgress == null) return child;
                            return Center(child: CircularProgressIndicator());
                          },
                          errorBuilder: (context, error, stackTrace) => Container(
                            height: 250,
                            color: Colors.grey[300],
                            child: Icon(Icons.broken_image, size: 50),
                          ),
                        ),
                      ),
                    );
                  }).toList(),
                ),

            ],
          ),
        ),
      ),
    );
  }
}
