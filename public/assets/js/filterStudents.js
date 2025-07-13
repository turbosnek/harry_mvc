class StudentFilter {
    constructor(inputSelector, studentsContainerSelector) {
        this.input = document.querySelector(inputSelector);
        this.studentsContainer = document.querySelector(studentsContainerSelector);
        if (!this.input) console.error("Input element nenalezen");
        if (!this.studentsContainer) console.error("Kontejner studentů nenalezen");

        this.allStudents = this.studentsContainer ? Array.from(this.studentsContainer.querySelectorAll(".one-student")) : [];

        this.init();
    }

    init() {
        if (!this.input || !this.studentsContainer) return;

        this.input.addEventListener("input", () => this.filter());
    }

    filter() {
        const query = this.input.value.toLowerCase();

        this.studentsContainer.textContent = "";

        const filtered = this.allStudents.filter(studentDiv => {
            const name = studentDiv.querySelector("h2").textContent.toLowerCase();
            return name.includes(query);
        });

        filtered.forEach(student => this.studentsContainer.appendChild(student));
    }
}

document.addEventListener("DOMContentLoaded", () => {
    new StudentFilter(".filter-input", ".all-students");
});