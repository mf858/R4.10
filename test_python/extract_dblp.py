import requests
import pandas as pd

def get_author_publications(author_name):
    """Récupère les publications d'un auteur depuis l'API DBLP."""
    url = f"https://dblp.org/search/author/api?q={author_name.replace(' ', '+')}&format=json"
    response = requests.get(url)
    data = response.json()

    # Vérifier si des résultats existent
    if 'result' in data and 'hits' in data['result']:
        hits = data['result']['hits']['hit']
        if hits:
            author_id = hits[0]['info']['url'].split('/')[-1]  # Récupérer l’ID de l’auteur
            pub_url = f"https://dblp.org/pid/{author_id}.xml"
            pub_response = requests.get(pub_url)
            return pub_response.text  # Retourne les données XML
    return None

# Test avec un auteur célèbre
author = "Yann LeCun"
xml_data = get_author_publications(author)

if xml_data:
    print("✅ Données XML récupérées avec succès !")
else:
    print("❌ Aucune publication trouvée.")
