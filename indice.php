<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jeu de Course de Voiture</title>
    <style>
        /* Styles CSS */
        body {
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #333;
            font-family: Arial, sans-serif;
            color: #fff;
        }

        #title {
            font-size: 36px;
            margin-bottom: 20px;
            color: #ffcc00;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        #game-container {
            width: 300px;
            height: 600px;
            background-color: #444;
            position: relative;
            overflow: hidden;
            border: 5px solid #555;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        }

        #road {
            width: 100%;
            height: 100%;
            position: relative;
            background: repeating-linear-gradient(
                transparent,
                transparent 50px,
                #fff 50px,
                #fff 100px
            );
            animation: moveRoad 2s linear infinite;
        }

        @keyframes moveRoad {
            0% { background-position: 0 0; }
            100% { background-position: 0 100px; }
        }

        #car {
            width: 50px;
            height: 100px;
            background: url('car_icon.png') no-repeat center center/cover;
            position: absolute;
            bottom: 20px;
            left: 125px;
        }

        .obstacle {
            width: 50px;
            height: 100px;
            position: absolute;
            top: -100px;
            background-size: cover;
        }

        .lane {
            width: 100px;
            height: 100%;
            position: absolute;
            top: 0;
            border-left: 2px dashed #fff;
            border-right: 2px dashed #fff;
        }

        .lane:nth-child(1) { left: 0; }
        .lane:nth-child(2) { left: 100px; }
        .lane:nth-child(3) { left: 200px; }

        #score-board {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 24px;
            color: #fff;
        }

        #high-scores {
            position: absolute;
            top: 60px;
            left: 20px;
            font-size: 18px;
            color: #fff;
        }

        #pause-message {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            color: #ffcc00;
            display: none;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        #game-over-message {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 36px;
            font-weight: bold;
            color: #ff0000;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            display: none;
            animation: bounce 1s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translate(-50%, -50%) scale(1); }
            50% { transform: translate(-50%, -50%) scale(1.2); }
        }

        .flower {
            position: absolute;
            width: 30px;
            height: 30px;
            background: url('flower.png') no-repeat center center/cover;
            animation: float 2s linear infinite;
        }

        @keyframes float {
            0% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0); }
        }

        /* Styles pour l'explosion */
        .explosion {
            position: absolute;
            width: 100px;
            height: 100px;
            background: url('explosion.png') no-repeat center center/cover;
            animation: explode 2s linear forwards; /* Durée de l'animation : 2 secondes */
        }

        @keyframes explode {
            0% { transform: scale(0); opacity: 1; }
            100% { transform: scale(2); opacity: 0; }
        }

        /* Styles pour les boutons */
        #help-button, #restart-button, #exit-button {
            background-color: #ffcc00;
            border: none;
            color: #333;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 18px;
            margin: 15px;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            transition: background-color 0.3s ease;
        }

        #help-button:hover, #restart-button:hover, #exit-button:hover {
            background-color: #ff9900;
        }

        #help-button {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        #restart-button {
            position: absolute;
            top: 70px;
            right: 20px;
        }

        #exit-button {
            position: absolute;
            top: 120px;
            right: 20px;
        }

        #help-modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #555;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            display: none;
            z-index: 1000;
        }

        #help-modal h2 {
            margin-top: 0;
            color: #ffcc00;
        }

        #help-modal p {
            margin: 10px 0;
        }

        #help-modal button {
            padding: 10px 20px;
            background-color: #ffcc00;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            z-index: 999;
        }
    </style>
</head>
<body>
    <div id="title">Racing Car par JOEL-ARIS</div>
    <button id="help-button" onclick="showHelp()">Aide</button>
    <button id="restart-button" onclick="restartGame()">Recommencer</button>
    <button id="exit-button" onclick="exitGame()">Exit</button>
    <div id="game-container">
        <div id="road">
            <div class="lane"></div>
            <div class="lane"></div>
            <div class="lane"></div>
        </div>
        <div id="car"></div>
        <div id="pause-message">PAUSE</div>
        <div id="game-over-message">Oups!! Vous avez perdu!!</div>
    </div>
    <div id="score-board">Score: <span id="score">0</span></div>
    <div id="high-scores">Meilleurs Scores: <span id="high-scores-list">Chargement...</span></div>

    <!-- Modal d'aide -->
    <div id="help-modal">
        <h2>Instructions</h2>
        <p>1. Utilise les touches fléchées <strong>gauche</strong> et <strong>droite</strong> pour déplacer la voiture.</p>
        <p>2. Appuie sur <strong>Espace</strong> pour mettre le jeu en pause.</p>
        <p>3. Évite les autres véhicules pour ne pas perdre.</p>
        <p>4. Tous les 20 véhicules évités, des fleurs apparaissent pour te féliciter.</p>
        <p>5. Ton score est enregistré et comparé aux meilleurs scores.</p>
        <button onclick="hideHelp()">Fermer</button>
    </div>
    <div id="overlay" onclick="hideHelp()"></div>

    <script>
        // JavaScript Code
        const car = document.getElementById('car');
        const lanes = [0, 100, 200];
        const scoreDisplay = document.getElementById('score');
        const highScoresList = document.getElementById('high-scores-list');
        const pauseMessage = document.getElementById('pause-message');
        const gameOverMessage = document.getElementById('game-over-message');
        const helpModal = document.getElementById('help-modal');
        const overlay = document.getElementById('overlay');
        const restartButton = document.getElementById('restart-button');
        const exitButton = document.getElementById('exit-button');
        let currentLane = 1;
        let score = 0;
        let gameInterval;
        let isPaused = false;
        let vehiclesPassed = 0;
        let obstacleSpeed = 5; // Vitesse initiale des obstacles

        function showHelp() {
            helpModal.style.display = 'block';
            overlay.style.display = 'block';
        }

        function hideHelp() {
            helpModal.style.display = 'none';
            overlay.style.display = 'none';
        }

        function restartGame() {
            gameOverMessage.style.display = 'none';
            location.reload(); // Recharge la page pour redémarrer le jeu
        }

        function exitGame() {
            if (confirm("Êtes-vous sûr de vouloir quitter le jeu ?")) {
                try {
                    window.close(); // Tente de fermer l'onglet
                } catch (e) {
                    alert("Impossible de fermer l'onglet. Veuillez le fermer manuellement.");
                }
            }
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'ArrowLeft' && currentLane > 0) {
                currentLane--;
                moveCar();
            } else if (event.key === 'ArrowRight' && currentLane < 2) {
                currentLane++;
                moveCar();
            } else if (event.key === ' ') {
                togglePause();
            }
        });

        function moveCar() {
            car.style.left = lanes[currentLane] + 'px';
        }

        function togglePause() {
            isPaused = !isPaused;
            pauseMessage.style.display = isPaused ? 'block' : 'none';
            if (isPaused) {
                clearInterval(gameInterval);
                document.getElementById('road').style.animationPlayState = 'paused';
            } else {
                document.getElementById('road').style.animationPlayState = 'running';
                startGame();
            }
        }

        function generateObstacle() {
            const obstacle = document.createElement('div');
            obstacle.className = 'obstacle';
            const vehicleTypes = ['car33.png', 'car22.png', 'car33.png', 'car44.png'];
            const randomVehicle = vehicleTypes[Math.floor(Math.random() * vehicleTypes.length)];
            obstacle.style.backgroundImage = `url('${randomVehicle}')`;
            obstacle.style.left = lanes[Math.floor(Math.random() * lanes.length)] + 'px';
            document.getElementById('road').appendChild(obstacle);
            moveObstacle(obstacle);
        }

        function moveObstacle(obstacle) {
            let top = -100;
            const obstacleInterval = setInterval(() => {
                if (isPaused) return;
                top += obstacleSpeed; // Utiliser la vitesse actuelle des obstacles
                obstacle.style.top = top + 'px';

                if (checkCollision(car, obstacle)) {
                    clearInterval(obstacleInterval);
                    clearInterval(gameInterval);
                    showExplosion(car.offsetLeft, car.offsetTop);
                    gameOverMessage.style.display = 'block';
                    saveScore(score);
                    loadHighScores(); // Charger les meilleurs scores après la fin du jeu
                }

                if (top > 600) {
                    clearInterval(obstacleInterval);
                    obstacle.remove();
                    vehiclesPassed++;
                    if (vehiclesPassed % 20 === 0) {
                        showFlowers();
                        increaseObstacleSpeed(); // Augmenter la vitesse des obstacles
                    }
                }
            }, 20);
        }

        function showExplosion(x, y) {
            const explosion = document.createElement('div');
            explosion.className = 'explosion';
            explosion.style.left = x + 'px';
            explosion.style.top = y + 'px';
            document.getElementById('game-container').appendChild(explosion);
            setTimeout(() => explosion.remove(), 2000); // Supprimer l'explosion après 2 secondes
        }

        function increaseObstacleSpeed() {
            obstacleSpeed += 1; // Augmenter la vitesse des obstacles
        }

        function checkCollision(element1, element2) {
            const rect1 = element1.getBoundingClientRect();
            const rect2 = element2.getBoundingClientRect();
            return !(
                rect1.bottom < rect2.top ||
                rect1.top > rect2.bottom ||
                rect1.right < rect2.left ||
                rect1.left > rect2.right
            );
        }

        function saveScore(score) {
            fetch('index.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'score=' + score,
            })
            .then(response => response.text())
            .then(data => {
                console.log(data);
            })
            .catch(error => {
                console.error('Erreur lors de la sauvegarde du score :', error);
            });
        }

        function loadHighScores() {
            fetch('index.php?action=get_scores')
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    highScoresList.innerHTML = data.map(score => score.score).join(', ');
                } else {
                    highScoresList.innerHTML = "Aucun score enregistré.";
                }
            })
            .catch(error => {
                console.error('Erreur lors du chargement des scores :', error);
                highScoresList.innerHTML = "Erreur lors du chargement des scores.";
            });
        }

        function showFlowers() {
            for (let i = 0; i < 10; i++) {
                const flower = document.createElement('div');
                flower.className = 'flower';
                flower.style.left = Math.random() * 300 + 'px';
                flower.style.top = Math.random() * 600 + 'px';
                document.getElementById('game-container').appendChild(flower);
                setTimeout(() => flower.remove(), 2000);
            }
        }

        function startGame() {
            gameInterval = setInterval(() => {
                if (!isPaused) {
                    score += 1;
                    scoreDisplay.textContent = score;
                    generateObstacle();
                }
            }, 1000);
        }

        loadHighScores();
        startGame();
    </script>
</body>
</html>