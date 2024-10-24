import 'package:flutter/material.dart';

ThemeData theme = ThemeData(
  colorScheme: const ColorScheme.light(
    surface: Color.fromRGBO(212, 211, 211, 1),
    primary: Color.fromRGBO(33, 37, 41, 1),
    secondary: Colors.black,
    tertiary: Colors.white,
    inversePrimary: Colors.white,
    
  ),
  textTheme: const TextTheme(
    bodyLarge: TextStyle(color: Colors.black),
  ),
);