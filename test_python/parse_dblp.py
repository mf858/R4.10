import pandas as pd
import xml.etree.ElementTree as ET
from io import StringIO

def parse_dblp_xml(xml_string):
    """Transforme le XML en DataFrame Pandas."""
    root = ET.parse(StringIO(xml_string)).getroot()
    publications = []

    for publ in root.findall("r"):
        title = publ.find(".//title").text if publ.find(".//title") is not None else "N/A"
        year = publ.find(".//year").text if publ.find(".//year") is not None else "N/A"
        journal = publ.find(".//journal").text if publ.find(".//journal") is not None else "N/A"
        authors = [author.text for author in publ.findall(".//author")]
        affiliations = [aff.text for aff.findall(".//author/@affiliation")]  # Si DBLP fournit les affiliations

        publications.append({
            "Title": title.replace("'", "''"),
            "Year": year,
            "Journal": journal.replace("'", "''"),
            "Authors": ", ".join(authors).replace("'", "''"),
            "Affiliations": ", ".join(affiliations).replace("'", "''") if affiliations else "N/A"
        })

    return pd.DataFrame(publications)

# Exemple d'utilisation
xml_data = """<dblp>
    <r>
        <title>Deep Learning Revolution</title>
        <year>2023</year>
        <journal>AI Journal</journal>
        <author>Yann LeCun</author>
        <author>Geoffrey Hinton</author>
    </r>
</dblp>"""

df = parse_dblp_xml(xml_data)
print(df.head())  # Afficher les premières publications
