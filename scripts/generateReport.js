/**
 * Smart Web Dev Report Generator (Styled)
 * ------------------------------------------
 * Generates PDF reports ONLY for TP folders without existing reports.
 * Applies a modern, clean style inspired by modern web dashboards.
 * Each TP folder gets its own report (reports/TPX_report.pdf).
 */

const puppeteer = require("puppeteer");
const fs = require("fs-extra");
const path = require("path");

(async () => {
  try {
    console.log("🚀 Starting selective report generation...");

    // Ensure reports folder exists
    fs.ensureDirSync("reports");

    // Get all TP folders
    const projects = fs
      .readdirSync(".")
      .filter((f) => /^TP\d+/i.test(f) && fs.lstatSync(f).isDirectory());

    if (projects.length === 0) {
      console.log("⚠️ No TP folders found (expected TP1, TP2, ...)");
      process.exit(0);
    }

    const browser = await puppeteer.launch({
      headless: true,
      args: ["--no-sandbox", "--disable-setuid-sandbox"],
    });

    for (const dir of projects) {
      const reportPath = `reports/${dir}_report.pdf`;

      // Skip if report already exists
      if (fs.existsSync(reportPath)) {
        console.log(`⏭️ Skipping ${dir}: report already exists.`);
        continue;
      }

      const htmlFiles = fs
        .readdirSync(dir)
        .filter((file) => file.endsWith(".html"));

      if (htmlFiles.length === 0) {
        console.log(`⚠️ Skipping ${dir}: no HTML files found.`);
        continue;
      }

      console.log(`📁 Processing ${dir}...`);

      // --- STYLED HTML SECTION ---
      // We wrap the student info and repo link in a "info-card" div
      // for styling.
      let section = `
<h1>${dir} Report</h1>
<div class="info-card">
  <h2>Student Information</h2>
  <ul>
    <li><strong>Student(s) Name(s):</strong> bilal siki</li>
  </ul>

  <h2>Project Repository</h2>
  <ul>
    <li><strong>GitHub Link:</strong> <a href="${process.env.GITHUB_SERVER_URL}/${process.env.GITHUB_REPOSITORY}" target="_blank">${process.env.GITHUB_SERVER_URL}/${process.env.GITHUB_REPOSITORY}</a></li>
  </ul>
</div>

<h2>Project Output</h2>
`;

      for (const file of htmlFiles) {
        const filePath = path.join(dir, file);
        const page = await browser.newPage();
        await page.goto(`file://${process.cwd()}/${filePath}`, {
          waitUntil: "networkidle0",
        });

        // Capture screenshot
        const screenshotPath = `${dir}_${file.replace(".html", "")}.png`;
        await page.screenshot({ path: screenshotPath, fullPage: true });
        await page.close();

        // Embed screenshot in a styled "screenshot-container" div
        const imageBase64 = fs.readFileSync(screenshotPath, "base64");
        const imageTag = `<div class="screenshot-container">
            <img src="data:image/png;base64,${imageBase64}" style="width: 100%; display: block; border-radius: 4px;">
          </div>`;

        console.log(`📸 Captured and embedded: ${screenshotPath}`);
        section += `<h3>${file}</h3>${imageTag}`;
      }

      section += `<h2>Notes</h2><hr>`;

      // --- STYLED HTML TEMPLATE ---
      // This HTML and CSS block is completely revamped to match the
      // modern dashboard look (light theme, cards, clean font).
      const htmlReport = `
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>${dir} Report</title>
  <style>
    /* Import the 'Inter' font for a modern look */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
    
    body { 
      font-family: 'Inter', Arial, sans-serif; 
      margin: 0;
      padding: 40px; 
      background-color: #F9FAFB; /* Light gray background */
      color: #1F2937; /* Dark gray text */
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }
    h1 { 
      font-size: 2.25rem; /* 36px */
      font-weight: 700;
      color: #111827; /* Near black */
      margin-bottom: 1rem;
      border-bottom: 2px solid #E5E7EB; /* Light border */
      padding-bottom: 0.5rem;
    }
    h2 { 
      font-size: 1.5rem; /* 24px */
      font-weight: 600;
      color: #1F2937; 
      margin-top: 0;
      margin-bottom: 1rem;
    }
    h3 { 
      font-size: 1.25rem; /* 20px */
      font-weight: 600;
      color: #374151; /* Medium gray */
      margin-top: 1.5rem; 
      margin-bottom: 0.75rem; 
    }
    /* Card style for info box */
    .info-card {
      background: #FFFFFF;
      border: 1px solid #E5E7EB;
      border-radius: 12px;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
      padding: 1.5rem 2rem;
      margin-bottom: 2rem;
    }
    .info-card h2 {
      font-size: 1.25rem;
      border-bottom: 1px solid #F3F4F6;
      padding-bottom: 0.75rem;
      margin-bottom: 1rem;
    }
    /* Card style for screenshots */
    .screenshot-container {
      background: #FFFFFF;
      border: 1px solid #E5E7EB;
      border-radius: 12px;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
      margin: 1.5rem 0;
      padding: 1rem; /* Padding around the image */
      overflow: hidden;
    }
    img {
      display: block;
      margin: auto;
      border-radius: 8px; /* Rounded corners for the screenshot itself */
      max-width: 100%;
    }
    ul {
      list-style-type: none;
      padding-left: 0;
      margin-top: 0.5rem;
    }
    li {
      margin-bottom: 0.5rem;
      color: #4B5563; /* Lighter text for list items */
    }
    a { 
      color: #2563EB; /* Blue links */
      text-decoration: none; 
      font-weight: 600;
    }
    a:hover { 
      text-decoration: underline; 
    }
    hr { 
      margin: 2.5rem 0; 
      border: none; 
      border-top: 1px solid #E5E7EB; 
    }
  </style>
</head>
<body>
  ${section}
</body>
</html>
`;
      // --- END OF STYLED TEMPLATE ---

      // Generate PDF for this TP
      const page = await browser.newPage();
      await page.setContent(htmlReport, { waitUntil: "networkidle0" });
      await page.pdf({
        path: reportPath,
        format: "A4",
        printBackground: true,
        margin: {
          top: "20px",
          right: "20px",
          bottom: "20px",
          left: "20px",
        },
      });
      await page.close();

      console.log(`✅ Generated: ${reportPath}`);
    }

    await browser.close();
    console.log("🎉 All new reports generated successfully!");
  } catch (error) {
    console.error("❌ Error generating reports:", error);
    process.exit(1);
  }
})();
