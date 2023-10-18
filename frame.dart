// ignore_for_file: file_names, unused_import, unnecessary_import, non_constant_identifier_names

import 'dart:async';
import 'dart:io';
import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:step_progress_indicator/step_progress_indicator.dart';
import 'package:shared_preferences/shared_preferences.dart';

import 'Frame8.dart' show Frame8;
import 'Frame10.dart' show Frame10;

class Frame9 extends StatefulWidget {
  const Frame9({Key? key}) : super(key: key);
  @override
  State<Frame9> createState() => Frame9State();
}

class Frame9State extends State<Frame9> {
  final Future<SharedPreferences> _prefs = SharedPreferences.getInstance();
  final myController2 = TextEditingController();
  bool submit = false;
  int value = 0;
  int newValue = 0;

  final List<bool> _list = [
    false,
    false,
    false,
    false,
    false,
    false,
    false,
    false,
    false,
    false,
    false,
    false,
    false,
    false,
    false,
    false,
    false,
    false
  ];

  int _count = 0;
  Widget CustomRadioButton(String text, int index) {
    return TextButton(
      onPressed: () {
        setState(() {
          _list[index] = !_list[index];
          if (_list[index]) {
            _count += 1;
          } else {
            _count -= 1;
          }

          if (_count > 2) {
            submit = true;
          } else {
            submit = false;
          }
        });
      },
      child: Padding(
          padding:
              const EdgeInsets.only(left: 14, right: 14, top: 8, bottom: 8),
          child: Text(
            text,
            style: TextStyle(
                color: _list[index] ? const Color(0xffffffff) : Colors.grey,
                fontSize: 16),
          )),
      style: ButtonStyle(
          shape: MaterialStateProperty.all(RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(5),
            side: BorderSide(
              color: _list[index] ? const Color(0xff549B4D) : Colors.grey,
              style: BorderStyle.solid,
            ),
          )),
          backgroundColor: MaterialStateProperty.all(_list[index]
              ? const Color(0xff549B4D)
              : const Color(0x00000000))),
    );
  }

  @override
  void initState() {
    // ignore: todo
    // TODO: implement initState
    super.initState();
  }

  @override
  void dispose() {
    // Clean up the controller when the widget is disposed.
    myController2.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    Widget Titulo = RichText(
      text: const TextSpan(
          text: '\n\n¿Qué te interesa? ',
          style: TextStyle(
            fontWeight: FontWeight.bold,
            color: Colors.black,
            fontSize: 24,
          )),
    );

    return GestureDetector(
        child: MaterialApp(
            home: Scaffold(
      backgroundColor: const Color(0xff89EEFA),
      body: ListView(
        padding:
            const EdgeInsets.only(top: 50, bottom: 10, left: 10, right: 10),
        shrinkWrap: true,
        children: [
          const StepProgressIndicator(
            totalSteps: 100,
            currentStep: 75,
            size: 6,
            padding: 0,
            selectedColor: Color(0xff5D6366),
            unselectedColor: Color.fromRGBO(204, 204, 204, 0.5),
            roundedEdges: Radius.circular(10),
          ),
          Padding(
              padding: const EdgeInsets.only(top: 5, left: 20),
              child: GestureDetector(
                child: RichText(
                  textAlign: TextAlign.left,
                  text: const TextSpan(
                      text: '<',
                      style: TextStyle(
                        fontWeight: FontWeight.bold,
                        color: Color(0xffFFFFFF),
                        fontSize: 30,
                      )),
                ),
                onTap: () {
                  Navigator.of(context).pop();
                },
              )),
          Container(
              alignment: Alignment.topLeft,
              child: Padding(
                  padding: const EdgeInsets.only(top: 0, left: 30, right: 30),
                  child: GestureDetector(
                    child: Titulo,
                  ))),
          Padding(
            padding: const EdgeInsets.only(top: 25, left: 30, right: 30),
            child: RichText(
              textAlign: TextAlign.left,
              text: const TextSpan(
                  text:
                      'Venga, ¡no te cortes! \nSelecciona al menos 3 opciones',
                  style: TextStyle(
                    fontWeight: FontWeight.bold,
                    color: Color(0xff7F7F7F),
                    fontSize: 12,
                  )),
            ),
          ),
          Container(
              padding: const EdgeInsets.only(top: 5),
              child: Row(
                mainAxisAlignment: MainAxisAlignment
                    .center, //Center Row contents horizontally,
                crossAxisAlignment: CrossAxisAlignment.center,
                children: <Widget>[
                  Container(
                      margin: const EdgeInsets.only(left: 0),
                      child: CustomRadioButton("Coches", 0)),
                  Container(
                      margin: const EdgeInsets.only(left: 5),
                      child: CustomRadioButton("Moda", 1)),
                  Container(
                      margin: const EdgeInsets.only(left: 5),
                      child: CustomRadioButton("Decoración", 2))
                ],
              )),
          Container(
              padding: const EdgeInsets.only(top: 5),
              child: Row(
                mainAxisAlignment: MainAxisAlignment
                    .center, //Center Row contents horizontally,
                crossAxisAlignment: CrossAxisAlignment.center,
                children: <Widget>[
                  Container(
                      margin: const EdgeInsets.only(left: 0),
                      child: CustomRadioButton("Crossfit", 3)),
                  Container(
                      margin: const EdgeInsets.only(left: 5),
                      child: CustomRadioButton("Zapatillas", 4)),
                  Container(
                      margin: const EdgeInsets.only(left: 5),
                      child: CustomRadioButton("Arte", 5)),
                ],
              )),
          Container(
              padding: const EdgeInsets.only(top: 5),
              child: Row(
                mainAxisAlignment: MainAxisAlignment
                    .center, //Center Row contents horizontally,
                crossAxisAlignment: CrossAxisAlignment.center,
                children: <Widget>[
                  Container(
                      margin: const EdgeInsets.only(left: 0),
                      child: CustomRadioButton("Comida", 6)),
                  Container(
                      margin: const EdgeInsets.only(left: 5),
                      child: CustomRadioButton("Música", 7)),
                  Container(
                      margin: const EdgeInsets.only(left: 5),
                      child: CustomRadioButton("Dieta Vegana", 8)),
                ],
              )),
          Container(
              padding: const EdgeInsets.only(top: 5),
              child: Row(
                mainAxisAlignment: MainAxisAlignment
                    .center, //Center Row contents horizontally,
                crossAxisAlignment: CrossAxisAlignment.center,
                children: <Widget>[
                  Container(
                      margin: const EdgeInsets.only(left: 0),
                      child: CustomRadioButton("Fotografia", 9)),
                  Container(
                      margin: const EdgeInsets.only(left: 5),
                      child: CustomRadioButton("Acuarelas", 10)),
                  Container(
                      margin: const EdgeInsets.only(left: 5),
                      child: CustomRadioButton("Cine", 11)),
                ],
              )),
          Container(
              padding: const EdgeInsets.only(top: 5),
              child: Row(
                mainAxisAlignment: MainAxisAlignment
                    .center, //Center Row contents horizontally,
                crossAxisAlignment: CrossAxisAlignment.center,
                children: <Widget>[
                  Container(
                      margin: const EdgeInsets.only(left: 0),
                      child: CustomRadioButton("Viajar", 12)),
                  Container(
                      margin: const EdgeInsets.only(left: 5),
                      child: CustomRadioButton("Golf", 13)),
                  Container(
                      margin: const EdgeInsets.only(left: 5),
                      child: CustomRadioButton("Motociclismo", 14)),
                ],
              )),
          Container(
              padding: const EdgeInsets.only(top: 5),
              child: Row(
                mainAxisAlignment: MainAxisAlignment
                    .center, //Center Row contents horizontally,
                crossAxisAlignment: CrossAxisAlignment.center,
                children: <Widget>[
                  Container(
                      margin: const EdgeInsets.only(left: 0),
                      child: CustomRadioButton("Libros", 15)),
                  Container(
                      margin: const EdgeInsets.only(left: 5),
                      child: CustomRadioButton("Tacones", 16)),
                  Container(
                      margin: const EdgeInsets.only(left: 5),
                      child: CustomRadioButton("Surf", 17)),
                ],
              )),
        ],
      ),
      bottomNavigationBar: Container(
        padding: const EdgeInsets.only(top: 0, left: 35, right: 30),
        child: Row(
          children: [
            Container(
              margin:
                  const EdgeInsets.only(top: 4, left: 4, right: 0, bottom: 4),
              padding: const EdgeInsets.all(4.0),
              child: const Image(
                  image: AssetImage("assets/images/ojo.png"),
                  width: 25,
                  height: 15),
            ),
            Container(
              margin:
                  const EdgeInsets.only(top: 4, left: 0, right: 75, bottom: 4),
              padding: const EdgeInsets.all(4.0),
              child: const Text(
                "Esto aparecera en tu perfil",
                style: TextStyle(color: Color(0xff7F7F7F), fontSize: 9),
              ),
            ),
            Container(
              margin: const EdgeInsets.all(4.0),
              padding: const EdgeInsets.all(8.0),
              child: ElevatedButton(
                onPressed: submit ? () => submitDataxx() : null,
                child: const Text('>'),
                style: ElevatedButton.styleFrom(
                  shape: const CircleBorder(),
                  padding: const EdgeInsets.all(13),
                  primary: const Color(0xff549B4D),
                  textStyle: const TextStyle(
                      color: Color(0xffDC1321),
                      fontSize: 30,
                      fontWeight: FontWeight.bold),
                ),
              ),
            )
          ],
        ),
      ),
    )));
  }

  submitDataxx() {
    _prefs.then((sp) {
      setState(() {
        sp.setInt('step', 7);
        Navigator.push(
          context,
          MaterialPageRoute(builder: (context) => const Frame10()),
        );
      });
    });
  }
}