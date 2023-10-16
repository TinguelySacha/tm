<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="tm4_0.css" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pendufoot - Ligue des champions 2022-2023</title>
  </head>
  <body>
    <div id="myModal" class="modal1">
      <div class="modal-content" id="modal-content">
        <span class="close">✗</span>
        <p>
          <br /><br />
          <h1>PENDUFOOT</h1>
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

    <div class="page-container">
      <div class="head" >
        <img id="aide" src="imagecdm/aide1.png" />
        <h1>PenduFoot</h1>
        <p id="player">
          1/5
        </p>
      </div>

      <div class="modal-container">
        <div class="overlay">
          <div class="modal">
            <h1 id="modal-score"></h1>
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
            placeholder="Rentrez une lettre :"
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
      function envoyerScores(userId, points1) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "envoyer_scores.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
          if (xhr.readyState === 4) {
            if (xhr.status === 200) {
              // Le serveur a confirmé la réception des scores
              // Vous pouvez effectuer des actions supplémentaires ici si nécessaire
              console.log("Scores envoyés avec succès au serveur.");
            } else {
              // Gestion des erreurs si la requête échoue
              console.error("Erreur lors de l'envoi des scores au serveur.");
            }
          }
        };

        // Envoyer l'ID de l'utilisateur, points1 et points2 dans les données de la requête
        const data = "userId=" + encodeURIComponent(userId) + "&points1=" + encodeURIComponent(points1);
        xhr.send(data);
      }

      function recupScores(userId, callback) {
          const xhr = new XMLHttpRequest();
          xhr.open("POST", "recup_scores.php", true);
          xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
          xhr.onreadystatechange = function () {
              if (xhr.readyState === 4 && xhr.status === 200) {
                  console.log(xhr.responseText)
                  const response = JSON.parse(xhr.responseText);
                  const points2 = response.otherUserScore;
                  var player;
                  console.log(response.otherUserId);
                  if (points2 !== null) {
                      console.log("Score de l'autre utilisateur : " + points2);
                      if (userId-response.otherUserId == -1) {
                        player = 1
                        localStorage.setItem("points1_1", points1);
                        localStorage.setItem("points2_1", points2);
                        localStorage.setItem("otherid1", response.otherUserId);
                        localStorage.setItem("userid1", userid);
                      }else{
                        player=2
                        localStorage.setItem("points1_2", points1);
                        localStorage.setItem("points2_2", points2);
                        localStorage.setItem("otherid2", response.otherUserId);
                        localStorage.setItem("userid2", userid);
                      }

                  } else {
                      // Aucun score trouvé pour l'autre utilisateur
                      console.log("Aucun score trouvé pour l'autre utilisateur.");
                  }
                  // Appeler le rappel avec les données récupérées
                  callback(points2);
                  $("modal-score").innerHTML = points1 + "-" + points2;
                  modalContainer.classList.toggle("active");
                  localStorage.setItem("otherid", response.otherUserId);
                  localStorage.setItem("userid", userid);
                  setTimeout(function () {
                    window.location.replace("socceronline1_0.html?p=" + player)
                  }, 5000);
              }
          };

          const data = "userId=" + encodeURIComponent(userId);
          xhr.send(data);
      }

      function enter(event) {
        pendu(event.key);
        i1.value = "";
      }
      var timer = setInterval(time, 1000);
      var joueur1;
      var joueur2;
      var nom;
      var oui;
      var temps = 30;
      var jeu = 1;
      var essai;
      var club;
      var p1 = true;
      var points1 = 0;
      var mistakes = 0;
      var userid = localStorage.getItem("userid");
      localStorage.removeItem("userid");
      const modalContainer = document.querySelector(".modal-container");
      const modalTriggers = document.querySelectorAll(".modal-trigger");

      var span = document.getElementsByClassName("close")[0];
      var modal1 = document.getElementById("myModal");
      var btn = document.getElementById("aide");

      btn.onclick = function () {
        modal1.style.display = "block";
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
                points1+=1
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
                $("player").innerHTML = jeu + "/5";
                lettre = "";
                setup();
                if (jeu > 5) {
                  var inputElement = document.getElementById("i1");
                  inputElement.style.display = "none";
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
              $("player").innerHTML = jeu + "/5";
              lettre = ""
              setup();
            }
            if (jeu > 5) {
              var inputElement = document.getElementById("i1");
              inputElement.style.display = "none";
            }
          }
        }
      }

      function time() {
        temps -= 1;
        $("temps").innerHTML = "TEMPS RESTANT : " + temps;
        if (temps <= 0) {
          temps = 99999;
          var inputElement = document.getElementById("i1");
          inputElement.style.display = "none";
          envoyerScores(userid, points1)
          setTimeout(function () {
            recupScores(userid, function (points2) {
            });
          }, 5000); // 5000 millisecondes équivalent à 5 secondes

          
        }
      }

      function $(id) {
        return document.getElementById(id);
      }
    </script>
  </body>
</html>
