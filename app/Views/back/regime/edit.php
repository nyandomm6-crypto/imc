<!-- back/regime/edit.php -->

<h2>Modifier régime</h2>

<form method="post" action="<?= base_url('back/regime/update/' . $regime['id']) ?>">

    <label>Nom du régime</label>
    <input type="text" name="libelle" value="<?= esc($regime['libelle']) ?>" required>

    <br><br>

    <label>Variation de poids</label>
    <input type="number" step="0.1" name="variation_poids"
           value="<?= esc($regime['variation_poids']) ?>">

    <hr>

    <!-- COMPOSITION -->
    <h3>Composition (%)</h3>

    <?php foreach ($aliments as $i => $a): 
        $val = $recettes[$a['id']] ?? 0;
    ?>
        <div style="margin-bottom:10px;">
            <label><?= esc($a['nom']) ?> :
                <span id="val_<?= $i ?>"><?= $val ?></span>%
            </label>

            <input 
                type="range"
                min="0"
                max="100"
                value="<?= $val ?>"
                name="recettes[<?= $i ?>][pourcentage]"
                data-index="<?= $i ?>"
                class="slider"
            >

            <input type="hidden" name="recettes[<?= $i ?>][aliment_id]" value="<?= $a['id'] ?>">
        </div>
    <?php endforeach; ?>

    <p id="total"></p>

    <hr>

    <!-- PRIX -->
    <h3>Prix par durée</h3>

    <div id="prix-container">
        <?php foreach ($prixList as $i => $p): ?>
            <div>
                <input type="number" name="prix[<?= $i ?>][duree_jours]" value="<?= $p['duree_jours'] ?>">
                <input type="number" step="0.01" name="prix[<?= $i ?>][prix]" value="<?= $p['prix'] ?>">
                <button type="button" onclick="this.parentElement.remove()">X</button>
            </div>
        <?php endforeach; ?>
    </div>

    <button type="button" onclick="addPrix()">+ Ajouter prix</button>

    <br><br>

    <button type="submit">Modifier</button>
</form>

<script>
let sliders = document.querySelectorAll('.slider');

function updateTotal() {
    let total = 0;

    sliders.forEach(s => {
        total += parseInt(s.value);
        document.getElementById('val_' + s.dataset.index).innerText = s.value;
    });

    document.getElementById('total').innerText = "Total: " + total + "%";
}

sliders.forEach(s => s.addEventListener('input', updateTotal));

// PRIX
let index = document.querySelectorAll('#prix-container div').length;

function addPrix() {
    document.getElementById('prix-container').innerHTML += `
        <div>
            <input type="number" name="prix[${index}][duree_jours]">
            <input type="number" step="0.01" name="prix[${index}][prix]">
            <button type="button" onclick="this.parentElement.remove()">X</button>
        </div>
    `;
    index++;
}
</script>