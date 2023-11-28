function openModal() {
    document.getElementById('myModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('myModal').style.display = 'none';
}

function deleteLogement() {
    // Ajoutez ici votre logique PHP pour supprimer le logement de la base de données
    alert('Logement supprimé avec succès !');
    closeModal();
}

