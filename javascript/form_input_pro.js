function toggleFields() {
    var typeParticulier = document.getElementById('particulier');
    var raisonSocialeField = document.getElementById('raisonSocialeField');
    var sirenField = document.getElementById('sirenField');
    var raisonSocialeinput = document.getElementById('raisonsociale');
    var sireninput = document.getElementById('siren');

    if (typeParticulier.checked) {
      raisonSocialeField.classList.add('hidden');
      sirenField.classList.add('hidden');
      raisonSocialeinput.removeAttribute("required");
      sireninput.removeAttribute("required");
    } else {
      raisonSocialeField.classList.remove('hidden');
      sirenField.classList.remove('hidden');
      raisonSocialeinput.setAttribute("required", "required");
      sireninput.setAttribute("required", "required");
    }
  }