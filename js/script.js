// Selecionando os botões e o modal
const deleteButtons = document.querySelectorAll('.button-exclude');
const modal = document.getElementById('deleteModal');
const cancelButton = document.getElementById('cancelButton');
const deleteForm = document.getElementById('deleteForm');
const idInput = document.getElementById('id_funcionario');

// Abrir o modal
deleteButtons.forEach(button => {
    button.addEventListener('click', function (e) {
        e.preventDefault();
        const id = this.getAttribute('data-id');
        idInput.value = id;
        modal.classList.add('show');
    });
});

// Fechar o modal ao clicar no botão "Cancelar"
cancelButton.addEventListener('click', function () {
    modal.classList.remove('show');
});

// Fechar o modal ao clicar fora da área modal
modal.addEventListener('click', function (e) {
    if (e.target === modal) {
        modal.classList.remove('show');
    }
});
