const express = require("express");
const bodyParser = require("body-parser");
const fs = require("fs");
const path = require("path");
const { Builder } = require("xml2js");

const app = express();
const port = 3000;

app.use(bodyParser.json());

app.get("/", (req, res) => {
    res.sendFile(path.join(__dirname, "index.html"));
});

app.post("/api/student", (req, res) => {
    const studentData = req.body;
    console.log("Received data:", studentData);

    fs.readFile("students.json", "utf8", (err, data) => {
        if (err && err.code === "ENOENT") {
            data = "[]"; 
        }

        const students = JSON.parse(data);
        students.push(studentData);

        fs.writeFile("students.json", JSON.stringify(students, null, 2), "utf8", (err) => {
            if (err) {
                return res.status(500).json({ message: "Error saving JSON data" });
            }
            console.log("Data saved to JSON");
        });
    });

    const builder = new Builder();
    const xml = builder.buildObject({ student: studentData });

    fs.appendFile("students.xml", xml + "\n", "utf8", (err) => {
        if (err) {
            return res.status(500).json({ message: "Error saving XML data" });
        }
        console.log("Data saved to XML");
        res.status(200).json({ message: "Data saved successfully to JSON and XML" });
    });
});

app.listen(port, () => {
    console.log(`Server is running at http://localhost:${port}`);
});