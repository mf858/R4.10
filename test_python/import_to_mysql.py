import mysql.connector
import pandas as pd

def connect_to_db():
    """Connexion à MySQL."""
    return mysql.connector.connect(
        host="localhost",
        user="root",
        password="root",  # Modifier avec votre mot de passe
        database="dblp_db"
    )

def insert_into_mysql(df):
    """Insère les données dans MySQL."""
    conn = connect_to_db()
    cursor = conn.cursor()

    for _, row in df.iterrows():
        sql = f"INSERT INTO publications (title, year, journal, authors, affiliations) VALUES (%s, %s, %s, %s, %s)"
        values = (row["Title"], row["Year"], row["Journal"], row["Authors"], row["Affiliations"])
        cursor.execute(sql, values)

    conn.commit()
    cursor.close()
    conn.close()
    print("✅ Données insérées dans MySQL avec succès !")

# Charger les données depuis un fichier SQL
df = pd.read_csv("dblp_publications.csv")
insert_into_mysql(df)
