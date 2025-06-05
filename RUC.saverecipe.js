function saveRecipe(recipeId, userId) {
    fetch('RUC.savingrecipes.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            recipeId: recipeId,
            userId: userId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Recipe saved successfully!');
            const saveButton = document.querySelector('.save-recipe button');
            saveButton.textContent = 'Remove Recipe';
            saveButton.onclick = () => removeRecipe(recipeId, userId);
            saveButton.classList.add('saved');
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error saving recipe');
    });
}

function removeRecipe(recipeId, userId) {
    if (!confirm('Are you sure you want to remove this recipe from your saved recipes?')) {
        return;
    }

    fetch('RUC.removesavedrecipe.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            recipeId: recipeId,
            userId: userId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Recipe removed successfully!');
            const saveButton = document.querySelector('.save-recipe button');
            saveButton.textContent = 'Save Recipe';
            saveButton.onclick = () => saveRecipe(recipeId, userId);
            saveButton.classList.remove('saved');
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error removing recipe');
    });
}