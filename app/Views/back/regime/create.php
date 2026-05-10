<!-- back/regime/create.php -->

<h2>Créer un régime</h2>

<form method="post" action="<?= base_url('back/regime/store') ?>">

    <!-- NOM -->
    <label>Nom du régime</label>
    <input type="text" name="libelle" required>

    <br><br>

    <!-- VARIATION POIDS -->
    <label>Variation de poids attendue (kg)</label>
    <input type="number" step="0.1" name="variation_poids" required>

    <hr>

    <!-- ===================== -->
    <!-- COMPOSITION (RECETTES)
    <!-- ===================== -->
    <h3>Composition du régime (%)</h3>

    <?php foreach ($aliments as $i => $a): ?>
        <div style="margin-bottom:10px;">
            <label><?= esc($a['nom']) ?> : 
                <span id="val_<?= $i ?>">0</span>%
            </label>

            <input 
                type="range"
                min="0"
                max="100"
                value="0"
                name="recettes[<?= $i ?>][pourcentage]"
                data-index="<?= $i ?>"
                class="slider"
            >

            <input type="hidden" name="recettes[<?= $i ?>][aliment_id]" value="<?= $a['id'] ?>">
        </div>
    <?php endforeach; ?>

    <p id="total">Total: 0%</p>

    <hr>

    <!-- ===================== -->
    <!-- PRIX DYNAMIQUE
    <!-- ===================== -->
    <h3>Prix par durée</h3>

    <div id="prix-container"></div>

    <button type="button" onclick="addPrix()">+ Ajouter prix</button>

    <br><br>

    <button type="submit">Créer</button>
</form>

<script>
let sliders = document.querySelectorAll('.slider');

function updateTotal() {
    let total = 0;

    sliders.forEach(slider => {
        let val = parseInt(slider.value);
        total += val;

        let index = slider.dataset.index;
        document.getElementById('val_' + index).innerText = val;
    });

    document.getElementById('total').innerText = "Total: " + total + "%";
}

sliders.forEach(s => {
    s.addEventListener('input', updateTotal);
});

// =====================
// PRIX DYNAMIQUE
// =====================
let index = 0;

function addPrix() {
    document.getElementById('prix-container').innerHTML += `
        <div style="margin-bottom:10px;">
            Durée (jours):
            <input type="number" name="prix[${index}][duree_jours]" required>

            Prix:
            <input type="number" step="0.01" name="prix[${index}][prix]" required>

            <button type="button" onclick="this.parentElement.remove()">X</button>
        </div>
    `;
    index++;
}
</script>