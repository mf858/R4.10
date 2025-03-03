import mysql.connector

def query_mysql(query):
    """Exécute une requête MySQL et affiche le résultat."""
    conn = mysql.connector.connect(
        host="localhost",
        user="root",
        password="root",
        database="dblp_db"
    )
    cursor = conn.cursor()
    cursor.execute(query)
    
    results = cursor.fetchall()
    for row in results:
        print(row)

    cursor.close()
    conn.close()

# Requêtes SQL pour analyser les publications
print("🔍 Publications sur le Machine Learning :")
query_mysql("SELECT * FROM publications WHERE title LIKE '%Machine Learning%';")

print("\n🔍 Publications en 2023 :")
query_mysql("SELECT * FROM publications WHERE year = 2023;")
