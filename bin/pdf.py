import pdfplumber
import json
import sys

def extract_text_from_pdf(pdf_path, page_number=None):
    text_data = {}
    try:
        with pdfplumber.open(pdf_path) as pdf:
            if page_number is not None:
                text_data[f'page_{page_number}'] = pdf.pages[page_number].extract_text()
            else:
                for i, page in enumerate(pdf.pages):
                    text_data[f'page_{i + 1}'] = page.extract_text()
    except Exception as e:
        return {"error": str(e)}
    return text_data

def extract_tables_from_pdf(pdf_path, page_number=None):
    table_data = {}
    try:
        with pdfplumber.open(pdf_path) as pdf:
            if page_number is not None:
                table_data[f'page_{page_number}'] = pdf.pages[page_number].extract_tables()
            else:
                for i, page in enumerate(pdf.pages):
                    table_data[f'page_{i + 1}'] = page.extract_tables()
    except Exception as e:
        return {"error": str(e)}
    return table_data

def extract_metadata_from_pdf(pdf_path):
    metadata = {}
    try:
        with pdfplumber.open(pdf_path) as pdf:
            metadata = pdf.metadata
    except Exception as e:
        return {"error": str(e)}
    return metadata

def extract_images_from_pdf(pdf_path, page_number=None):
    image_data = {}
    try:
        with pdfplumber.open(pdf_path) as pdf:
            if page_number is not None:
                image_data[f'page_{page_number}'] = [img["src"] for img in pdf.pages[page_number].images]
            else:
                for i, page in enumerate(pdf.pages):
                    image_data[f'page_{i + 1}'] = [img["src"] for img in page.images]
    except Exception as e:
        return {"error": str(e)}
    return image_data

if __name__ == "__main__":
    command = sys.argv[1]
    pdf_path = sys.argv[2]
    page_number = int(sys.argv[3]) if len(sys.argv) > 3 and sys.argv[3].isdigit() else None

    if command == "text":
        output = extract_text_from_pdf(pdf_path, page_number)
    elif command == "tables":
        output = extract_tables_from_pdf(pdf_path, page_number)
    elif command == "metadata":
        output = extract_metadata_from_pdf(pdf_path)
    elif command == "images":
        output = extract_images_from_pdf(pdf_path, page_number)
    else:
        output = {"error": "Invalid command"}

    print(json.dumps(output))
