import 'package:estaciona_mais/theme/theme.dart';
import 'package:flutter/material.dart';



class ThemeProvider extends ChangeNotifier{
  //tema padrao
  ThemeData _themeData = theme; 

  ThemeData get themeData => _themeData;

  set themeData(ThemeData themeData){
    _themeData = themeData;
    //atualiza a interface
    notifyListeners();
  }
}