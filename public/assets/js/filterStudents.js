document.addEventListener("DOMContentLoaded", () => {
    const input = document.querySelector(".filter-input");
    const allStudentsDiv = document.querySelector(".all-students");

    // Ulož originální seznam studentů z DOM (pro obnovu)
    const allOneStudents = Array.from(allStudentsDiv.querySelectorAll(".one-student"));

    input.addEventListener("input", () => {
        const inputText = input.value.toLowerCase();

        // Vyčistíme výpis
        allStudentsDiv.textContent = "";

        // Filtrujeme podle jména (obsah h2)
        const filteredStudents = allOneStudents.filter(studentDiv => {
            const name = studentDiv.querySelector("h2").textContent.toLowerCase();
            return name.includes(inputText);
        });

        // Vykreslíme jen filtrované
        filteredStudents.forEach(studentDiv => {
            allStudentsDiv.appendChild(studentDiv);
        });

        // Pokud je filtr prázdný, zobrazíme všechny (funguje díky uchování allOneStudents)
    });
});