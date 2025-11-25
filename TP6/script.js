document.addEventListener('DOMContentLoaded', () => {
    
    // ===============================================
    // LOGIQUE DE L'EXERCICE 1: Manipulation du DOM
    // ===============================================
    function runExercise1() {
        const root = document.getElementById('root-exercise-1');
        
        // 1. Crée une nouvelle div
        const maDiv = document.createElement('div');
        maDiv.classList.add('container-dom');
        
        // 2. Crée un paragraphe p
        const monParagraphe = document.createElement('p');
        monParagraphe.classList.add('paragraph-dom');
        
        // 3. Ajoute du texte initial
        monParagraphe.textContent = 'Ceci est un paragraphe.';
        
        // Structure: p dans div, div dans root
        maDiv.appendChild(monParagraphe);
        root.appendChild(maDiv);
        
        
        // 4. Modification le texte
        monParagraphe.textContent = 'Le texte a été modifié.';
        
        
        // 5. Modification du style CSS
        // Changez le style du paragraphe pour qu'il ait une couleur de fond « lightblue » et que le texte soit centré.
        monParagraphe.style.backgroundColor = 'lightblue';
        monParagraphe.style.textAlign = 'center';
        
        
        
        // 6. Ajout d'un événement
        // Lorsqu'un utilisateur clique sur la div, le texte du paragraphe doit changer
        maDiv.addEventListener('click', () => {
            monParagraphe.textContent = 'Un clic a été détecté';    
            monParagraphe.style.backgroundColor = '#0c60e7ff';
            monParagraphe.style.color = '#ebebebff';
        });
    }

    // ===============================================
    // LOGIQUE DE L'EXERCICE 2: To-do List
    // ===============================================
    function runExercise2() {
        const taskForm = document.getElementById('task-form');
        const taskInput = document.getElementById('task-input');
        const taskList = document.getElementById('task-list');

        // Gère la soumission du formulaire
        taskForm.addEventListener('submit', function(e) {
            e.preventDefault(); 
            const taskText = taskInput.value.trim();

            if (taskText !== '') {
                addTask(taskText);
                taskInput.value = ''; // Réinitialise le champ
            }
        });

        function addTask(text) {
            // Créer l'élément de la tâche (li)
            const listItem = document.createElement('li');
            
            // Récupère la valeur du champ de texte et ajoute-la à la liste
            const taskSpan = document.createElement('span');
            taskSpan.textContent = text;
            listItem.appendChild(taskSpan);

            // Créer les boutons d'action (Accomplie et Supprimer)
            const actionButtons = document.createElement('div');
            actionButtons.classList.add('action-buttons');
            
            const completeBtn = document.createElement('button');
            completeBtn.textContent = 'Accomplie';
            completeBtn.classList.add('complete-btn');

            const deleteBtn = document.createElement('button');
            deleteBtn.textContent = 'Supprimer';
            deleteBtn.classList.add('delete-btn');

            actionButtons.appendChild(completeBtn);
            actionButtons.appendChild(deleteBtn);
            listItem.appendChild(actionButtons);
            taskList.appendChild(listItem); // Ajoute à la liste <ul>
            
            // Gérer l'événement 'Accomplie'
            completeBtn.addEventListener('click', () => {
                // Ajoute une classe CSS pour indiquer visuellement
                listItem.classList.toggle('completed');
            });

            // Gérer l'événement 'Supprimer'
            deleteBtn.addEventListener('click', () => {
                // Supprime la tâche de la liste
                taskList.removeChild(listItem);
            });
        }
    }
    
    // Lancer les deux exercices
    runExercise1();
    runExercise2();
});