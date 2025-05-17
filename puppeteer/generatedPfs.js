const puppeteer = require('puppeteer');
const fs = require('fs');
const path = require('path');

const massProd = process.argv[2]; // get massProd from CLI arg

if (!massProd) {
  console.error('MassProd argument is required');
  process.exit(1);
}

(async () => {
  const outputDir = path.resolve(__dirname, '..', 'public', 'files', massProd);
  const outputPath = path.join(outputDir, `MPI_Report_${massProd}.pdf`);

  // Create folder if not exists
  fs.mkdirSync(outputDir, { recursive: true });

  const browser = await puppeteer.launch();
  const page = await browser.newPage();

  // Change URL to your Laravel route that renders the print view of this massProd
  const url = `http://127.0.0.1:8000/print/preview/${massProd}`;

  await page.goto(url, { waitUntil: 'networkidle0' });

  await page.pdf({
    path: outputPath,
    format: 'A4',
    printBackground: true,
  });

  await browser.close();

  console.log(`PDF saved to: ${outputPath}`);
})();
