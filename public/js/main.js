document.addEventListener('DOMContentLoaded', function () {
  // navbar mobile 
  const button = document.querySelector(".mobile-nav-toggle");
  button.addEventListener("click", (event) => {
    let body = document.querySelector("body")
    body.classList.toggle('mobile-nav-active')
  });

  // initialisation de "simpleTable"
  const dataTable = document.getElementById('dataTable');
  if (dataTable) {
    new simpleDatatables.DataTable(dataTable);
  }

  // si l'option "Autre" est sélectionnée, afficher un champ de texte supplémentaire
  const selectElement = document.getElementById('type_module');
  if (selectElement != null) {
    selectElement.addEventListener('change', function () {
      const selectedOptions = Array.from(selectElement.selectedOptions).map(option => option.value);
      if (selectedOptions.includes('autre')) {
        const autreTypeModuleContainer = document.getElementById('autre_type_module_container');
        autreTypeModuleContainer.style.display = 'block';
      } else {
        const autreTypeModuleContainer = document.getElementById('autre_type_module_container');
        autreTypeModuleContainer.style.display = 'none';
      }
    });
  }

  function update_alerts() {  // mettre a jour le nombre d'alertes en temps réel
    $.ajax({
      url: 'http://localhost:8000/api/modules/panne',
      method: 'GET',
      success: function(response) {
          let notif_elmnt = document.querySelector(".notification")
          if (response.nb_panne > 0) {
              notif_elmnt.innerHTML = '<span class="notification-count">'+response.nb_panne+'</span>'
          } else {
              notif_elmnt.innerHTML = ""
          }
      },
      error: function(xhr, status, error) {
          console.error(error);
      }
    });
  }
  setInterval(update_alerts, 5000)
});