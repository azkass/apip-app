import {
    addActor,
    deleteLastActor,
    saveActor,
    addCustomActor,
    addActivity,
    deleteLastActivity,
    loadExistingData,
    preview,
} from "./graph.js";

document.addEventListener("DOMContentLoaded", () => {
    const prosedurContainer = document.getElementById("prosedur-container");
    const prosedurIsi = prosedurContainer?.dataset.prosedurIsi;

    if (prosedurIsi && prosedurIsi !== "") {
        try {
            const jsonData = JSON.parse(prosedurIsi);

            if (
                jsonData &&
                jsonData.actorName &&
                jsonData.actorName.length > 0
            ) {
                // Tambahkan actor forms sesuai jumlah actor
                for (let i = 0; i < jsonData.actorName.length; i++) {
                    addActor();
                }

                // Isi nilai dropdown actor
                const actorSelects = document.querySelectorAll(".actor-select");
                actorSelects.forEach((select, index) => {
                    if (index < jsonData.actorName.length) {
                        const actorValue = jsonData.actorName[index];

                        const optionExists = Array.from(select.options).some(
                            (option) => option.value === actorValue,
                        );

                        if (optionExists) {
                            select.value = actorValue;
                        } else if (actorValue) {
                            select.value = "new-actor";
                            addCustomActor(select);
                            const customInput =
                                select.parentElement.querySelector(
                                    ".custom-actor-input",
                                );
                            if (customInput) {
                                customInput.value = actorValue;
                                customInput.classList.remove("hidden");
                            }
                        }
                    }
                });

                // Load aktivitas dari data JSON
                loadExistingData(jsonData);

                // Simpan actor tapi cegah overwrite aktivitas
                window.doNotOverwriteActivities = true;
                saveActor();
                window.doNotOverwriteActivities = false;
            } else {
                addActor();
            }
        } catch (e) {
            console.error("Error parsing JSON data:", e);
            addActor();
        }
    } else {
        addActor();
    }

    // Event listeners
    document.querySelector("#add-actor")?.addEventListener("click", addActor);
    document
        .querySelector("#delete-last-actor")
        ?.addEventListener("click", deleteLastActor);
    document.querySelector("#save-actor")?.addEventListener("click", saveActor);
    document
        .querySelector("#add-activity")
        ?.addEventListener("click", addActivity);
    document
        .querySelector("#delete-last-activity")
        ?.addEventListener("click", deleteLastActivity);
    document.querySelector("#preview")?.addEventListener("click", preview);

    // Dropdown pelaksana yang memilih "Pelaksana Baru"
    document
        .querySelector("#formContainer")
        ?.addEventListener("change", (event) => {
            if (event.target.classList.contains("actor-select")) {
                addCustomActor(event.target);
            }
        });
});
