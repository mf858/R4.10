import pandas as pd

def export_to_sql(df, filename="dblp_data.sql"):
    """Génère un fichier SQL à partir d'un DataFrame."""
    with open(filename, "w", encoding="utf-8") as f:
        f.write("USE dblp_db;\n")
        for _, row in df.iterrows():
            sql = f"INSERT INTO publications (title, year, journal, authors, affiliations) VALUES ('{row['Title']}', {row['Year']}, '{row['Journal']}', '{row['Authors']}', '{row['Affiliations']}');\n"
            f.write(sql)

    print(f"📂 Fichier SQL généré : {filename}")

# Tester avec un DataFrame fictif
data = {
    "Title": ["Deep Learning Revolution"],
    "Year": [2023],
    "Journal": ["AI Journal"],
    "Authors": ["Yann LeCun, Geoffrey Hinton"],
    "Affiliations": ["NYU, Google DeepMind"]
}

df = pd.DataFrame(data)
export_to_sql(df)
