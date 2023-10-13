<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="tm3_7.css" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pendufoot - Quiz sur le football en duel</title>
  </head>
  <body>
    <div id="myModal" class="modal1">
      <div class="modal-content" id="modal-content">
        <span class="close">✗</span>
        <p>
          <br /><br />
          <h1>PenduFoot</h1>
          <br /><br>
          Jouez au jeu du pendu avec les 145 joueurs les mieux notés de fifa 23 pour tester vos connaissances footballistiques
          !
          <br /><br>
          Le but du jeu est de trouver plus de joueurs que l'autre utilisateur afin de partir avec un avantage à la manche suivante ! 
          <br /><br>
          Vous disposerez donc de 30 secondes pour trouver le maximum de joueurs possibles avec les informations qui vous sont données sur lui.
        </p>
      </div>
    </div>

    <div id="Modalp1" class="modal1">
      <div class="modal-content" id="modal-content">
        <p>C'est au tour du joueur 1 !</p><br>
        <button class="continuer" id="btnp1">Continuer</button>
      </div>
    </div>

    <div id="Modalp2" class="modal1">
      <div class="modal-content" id="modal-content">
        <p>C'est au tour du joueur 2 !</p><br>
        <button class="continuer" id="btnp2">Continuer</button>
      </div>
    </div>

    <div class="page-container">
      <div class="head" >
        <img id="aide" src="imagecdm/aide1.png" />
        <h1>PenduFoot</h1>
        <p id="player">
          JOUEUR 1 <br />
          1/5
        </p>
      </div>

      <div class="modal-container">
        <div class="overlay">
          <div class="modal">
            <h1 id="modal-score"></h1>
            <a href="soccerstar2_5.html">
              <button class="continuer">Continuer</button>
            </a>
          </div>
        </div>
      </div>

      <div class="pendu-container">
        <div class="image-container">
          <img id="pays" src="" />
          <img id="club" src="" />
        </div>
        <div class="txt-container">
          <p id="temps">TEMPS RESTANT : 30</p>
          <p id="utilise"></p>
          <p id="nom">NOM X</p>
          <span class="football"><img src="imagecdm/football.png" alt="football" id="err1"><img src="imagecdm/football.png" alt="football" id="err2">
          <img src="imagecdm/football.png" alt="football" id="err3"><img src="imagecdm/football.png" alt="football" id="err4"><img src="imagecdm/football.png" alt="football" id="err5"></span>
          <input
            type="text"
            id="i1"
            onkeyup="enter(event)"
            placeholder="   Entrez une lettre :"
          />
        </div>
      </div>
    </div>
    <?php
      $servername ="localhost";
      $username ="DBuser9_77";
      $password ="12345";
      $dbname ="DB9db77";
      $bdd = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      $reponse = $bdd->query('SELECT * FROM joueurs');
      $results = $reponse->fetchAll(PDO::FETCH_ASSOC);
      echo '<script>';
      echo 'const liste_joueurs = ' . json_encode($results) . ';';
      echo '</script>';
      $reponse->closeCursor(); 
    ?> 
    <script>

      function enter(event) {
        pendu(event.key);
        i1.value = "";
      }
      var joueur1;
      var joueur2;
      var nom;
      var oui;
      var temps = 30;
      var jeu = 1;
      var essai;
      var player = 1;
      var club;
      var p1 = true;
      var p2 = true;
      var points1 = 0;
      var points2 = 0;
      var mistakes = 0;
      const modalContainer = document.querySelector(".modal-container");
      const modalTriggers = document.querySelectorAll(".modal-trigger");

      var span = document.getElementsByClassName("close")[0];
      var modal1 = document.getElementById("myModal");
      var modalp1 = document.getElementById("Modalp1");
      var modalp2 = document.getElementById("Modalp2");
      var btn = document.getElementById("aide");
      var btnp1 = document.getElementById("btnp1");
      var btnp2 = document.getElementById("btnp2");

      modalp1.style.display = "block";
      
      btn.onclick = function () {
        modal1.style.display = "block";
      };
      btnp1.onclick = function () {
        modalp1.style.display = "none";
        temps = 30;
        var timer = setInterval(time, 1000);
      };
      btnp2.onclick = function () {
        modalp2.style.display = "none";
        temps = 30;
        clearInterval(timer);
        var timer2 = setInterval(time, 1000);
      };
      span.onclick = function () {
        modal1.style.display = "none";
      };

      setup();
      function setup() {
        $("utilise").innerHTML = "";
        const randnumber = Math.floor(Math.random() * liste_joueurs.length);
        const randplayer = liste_joueurs[randnumber];
        joueur1 = randplayer.nom

        var editp = document.getElementById("pays");
        editp.src = "imagecdm/" + randplayer.pays + '.png';

        var editc = document.getElementById("club");
        editc.src = "imageldc/" + randplayer.equipe + '.png';

        joueur2 = joueur1;
        nom = "";
        for (let b2 = 0; b2 < joueur1.length; b2++) {
          if (joueur1[b2] == " ") {
            nom += " ";
          } else if (joueur1[b2] == ".") {
            nom += ".";
          } else if (joueur1[b2] == "-") {
            nom += "-";
          } else {
            nom += "◉";
          }
        }
        $("nom").innerHTML = nom;

        oui = 0;
        essai = 0;
      }

      function pendu(lettre) {
        essai += 1;

        lettre = lettre.toLowerCase();
        oui = 0;

        if (lettre.length <= 1 && typeof lettre === "string") {
          for (let index = 0; index < joueur1.length; index++) {
            if (lettre == joueur1[index]) {
              joueur2 = joueur1.replace(joueur1[index], "");
              nom = nom.split("");
              nom[index] = lettre;
              nom = nom.join("");
              oui = 1;
              $("nom").innerHTML = nom;
              if (nom == joueur1) {
                jeu += 1;
                if (player == 1) {
                  points1+=1
                }else{points2+=1}
                mistakes = -1
                var editx = document.getElementById("err1");
                editx.src = "imagecdm/football.png";
                editx = document.getElementById("err2");
                editx.src = "imagecdm/football.png";
                editx = document.getElementById("err3");
                editx.src = "imagecdm/football.png";
                editx = document.getElementById("err4");
                editx.src = "imagecdm/football.png";
                editx = document.getElementById("err5");
                editx.src = "imagecdm/football.png";
                $("player").innerHTML =
                  "JOUEUR " + player + "<br>" + jeu + "/5";
                lettre = "";
                setup();
                if (jeu > 5 && player == 1) {
                  jeu = 1;
                  player = 2;
                  mistakes = -1;
                  lettre = "";
                  var editx = document.getElementById("err1");
                  editx.src = "imagecdm/football.png";
                  editx = document.getElementById("err2");
                  editx.src = "imagecdm/football.png";
                  editx = document.getElementById("err3");
                  editx.src = "imagecdm/football.png";
                  editx = document.getElementById("err4");
                  editx.src = "imagecdm/football.png";
                  editx = document.getElementById("err5");
                  editx.src = "imagecdm/football.png";
                  temps = 99999;
                  document.getElementById("i1").blur();
                  modalp2.style.display = "block";
                  $("player").innerHTML =
                    "JOUEUR " + player + "<br>" + jeu + "/5";
                }
                if (jeu > 5 && player == 2) {
                  temps = 99999;
                  document.getElementById("i1").blur();
                  document.getElementById("i1").style.display = "none";
                  localStorage.setItem("points1", points1);
                  localStorage.setItem("points2", points2);
                  $("modal-score").innerHTML = points1 + "-" + points2;
                  modalContainer.classList.toggle("active");
                }
              }
            }
          }

          if (oui == 0) {
            $("utilise").innerHTML += " " + lettre + " /";
            mistakes+=1
            var editr = document.getElementById("err"+mistakes);
            editr.src = "imagecdm/crossedball.png";
            if (mistakes==5) {
              mistakes = -1
              jeu+=1
              var editx = document.getElementById("err1");
              editx.src = "imagecdm/football.png";
              editx = document.getElementById("err2");
              editx.src = "imagecdm/football.png";
              editx = document.getElementById("err3");
              editx.src = "imagecdm/football.png";
              editx = document.getElementById("err4");
              editx.src = "imagecdm/football.png";
              editx = document.getElementById("err5");
              editx.src = "imagecdm/football.png";
              $("player").innerHTML =
                  "JOUEUR " + player + "<br>" + jeu + "/5";
              lettre = ""
              setup();
            }
            if (jeu > 5 && player == 1) {
              document.getElementById("i1").blur();
              jeu = 1;
              player = 2;
              temps = 99999;
              var editx = document.getElementById("err1");
              editx.src = "imagecdm/football.png";
              editx = document.getElementById("err2");
              editx.src = "imagecdm/football.png";
              editx = document.getElementById("err3");
              editx.src = "imagecdm/football.png";
              editx = document.getElementById("err4");
              editx.src = "imagecdm/football.png";
              editx = document.getElementById("err5");
              editx.src = "imagecdm/football.png";
              modalp2.style.display = "block";
              $("player").innerHTML =
              "JOUEUR " + player + "<br>" + jeu + "/5";
            }
            if (jeu > 5 && player == 2) {
              document.getElementById("i1").blur();
              document.getElementById("i1").style.display = "none";
              temps = 99999;
              localStorage.setItem("points1", points1);
              localStorage.setItem("points2", points2);
              $("modal-score").innerHTML = points1 + "-" + points2;
              modalContainer.classList.toggle("active");
            }
          }
        }
      }

      function time() {
        temps -= 1;
        $("temps").innerHTML = "TEMPS RESTANT : " + temps;
        if (temps <= 0 && player == 1) {
          document.getElementById("i1").blur();
          jeu = 1;
          temps=99999;
          player = 2;
          mistakes = -1
          lettre = ""
          setup();
          modalp2.style.display = "block";
          $("player").innerHTML = "JOUEUR " + player + "<br>" + jeu + "/5";
        }

        if (temps <= 0 && player == 2) {
          temps = 99999;
          document.getElementById("i1").blur();
          document.getElementById("i1").style.display = "none";
          localStorage.setItem("points1", points1);
          localStorage.setItem("points2", points2);
          $("modal-score").innerHTML = points1 + "-" + points2;
          modalContainer.classList.toggle("active");
        }
      }

      function $(id) {
        return document.getElementById(id);
      }
    </script>
  </body>
</html>
