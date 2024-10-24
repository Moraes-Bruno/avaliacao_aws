import 'package:flutter/material.dart';
import 'package:estaciona_mais/view/home.dart';
import 'package:provider/provider.dart';
import 'package:estaciona_mais/theme/theme_provider.dart';

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      title: 'Estaciona Mais',
     theme: Provider.of<ThemeProvider>(context).themeData,
      home: MyHomePage(),
    );
  }
}