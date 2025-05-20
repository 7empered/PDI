document.getElementById("studentForm").addEventListener("submit", function(event) {
    event.preventDefault();

    const fullName = document.getElementById("fullName").value;
    const gender = document.querySelector('input[name="gender"]:checked').value;
    const group = document.getElementById("group").value;
    const studyProgram = document.getElementById("studyProgram").value;

    const studentData = {
        fullName: fullName,
        gender: gender,
        group: group,
        studyProgram: studyProgram
    };

    fetch("/api/student", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(studentData)
    })
    .then(response => response.json())
    .then(data => {
        alert("Data submitted successfully!");
        console.log(data);
    })
    .catch(error => {
        console.error("Error:", error);
    });
});