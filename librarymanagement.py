          
from datetime import datetime, timedelta

# =====================================
# STUDENT DICTIONARY
# =====================================

students = {
    "224703366": {
        "name": "Sherith",
        "password": "pass123"
    },
    "220678900": {
        "name": "Siamang",
        "password": "SiaKhoza@05"
    },
    "220111110": {
        "name": "Isaiah",
        "password": "Isaiah@11"
    }
}

# =====================================
# BOOK DICTIONARY
# =====================================

books = {
    "The Great Gatsby": {
        "author": "John Smith",
        "date_released": "2020",
        "available": True,
        "type": "free"
    },
    "48 Laws of Power": {
        "author": "Jane Doe",
        "date_released": "2021",
        "available": False,
        "type": "free"
    },
    "Rich Dad, Poor Dad": {
        "author": "Mike Brown",
        "date_released": "2022",
        "available": True,
        "type": "rent",
        "price": 150
    },
    "Why did I get married ?": {
        "author": "Sarah Wilson",
        "date_released": "2023",
        "available": True,
        "type": "rent",
        "price": 200
    }
}

# =====================================
# CONFERENCE ROOMS DICTIONARY
# =====================================

conference_rooms = {
    "Room A": {
        "capacity": 10,
        "available": True
    },
    "Room B": {
        "capacity": 20,
        "available": False
    },
    "Room C": {
        "capacity": 15,
        "available": True
    }
}


# =====================================
# LOGIN FUNCTION
# =====================================

def login():

    max_attempts = 3
    attempts = 0

    while attempts < max_attempts:

        print("\n===== UNIVERSITY LIBRARY LOGIN =====")

        student_number = input("Enter Student Number: ")
        password = input("Enter Password: ")

        if student_number in students:

            if students[student_number]["password"] == password:

                print(f"\nWelcome {students[student_number]['name']}!")
                return student_number

            else:
                attempts += 1

                remaining = max_attempts - attempts

                if remaining > 0:
                    print(f"\nIncorrect password.")
                    print(f"Attempts remaining: {remaining}")

        else:
            attempts += 1

            remaining = max_attempts - attempts

            if remaining > 0:
                print("\nStudent number not found.")
                print(f"Attempts remaining: {remaining}")

    print("\n===================================")
    print("MAXIMUM LOGIN ATTEMPTS REACHED")
    print("Access Denied")
    print("Please contact Admin: 0754967643")
    print("===================================")

    return None



# =====================================
# SEARCH AND BORROW/RENT BOOKS
# =====================================

def search_book():

    book_name = input("\nEnter Book Name: ")

    if book_name in books:

        print("\n===== BOOK DETAILS =====")
        print("Title:", book_name)
        print("Author:", books[book_name]["author"])
        print("Released:", books[book_name]["date_released"])

        # FREE BOOKS
        if books[book_name]["type"] == "free":

            if books[book_name]["available"]:

                choice = input(
                    "\nBook is available. Borrow for 2 weeks? (yes/no): "
                )

                if choice.lower() == "yes":

                    due_date = datetime.now() + timedelta(days=14)

                    books[book_name]["available"] = False

                    print("\nBook Borrowed Successfully!")
                    print(
                        "Return Date:",
                        due_date.strftime("%d-%m-%Y")
                    )
                    # COLLECTION DETAILS
                    print("\n===== COLLECTION DETAILS =====")
                    print("Please collect your book at the VUT Library.")
                    print("Collection Desk: Main Circulation Desk")
                    print("Library Hours: 08:00 - 16:00")
                    print("Bring your Student Card for verification.")

                else:
                    print("Borrow cancelled.")

            else:
                print("\nBook currently unavailable.")

        # RENTAL BOOKS
        elif books[book_name]["type"] == "rent":

            price = books[book_name]["price"]

            print("\n===== RENTAL BOOK =====")
            print(f"Rental Fee: R{price}")
            print("Rental Period: 3 Months")
            print("\n===== ONLINE PAYMENT DETAILS =====")
            print("VUT Account Number: 1975880534")

            choice = input(
                "Would you like to rent this book? (yes/no): "
            )

            if choice.lower() == "yes":

                payment = float(
                    input("Enter payment amount: R")
                )

                if payment >= price:

                    return_date = datetime.now() + timedelta(days=90)

                    print("\nPayment Successful!")
                    print("Book Rented Successfully!")
                    print(
                        "Return Date:",
                        return_date.strftime("%d-%m-%Y")
                    )

                    change = payment - price

                    if change > 0:
                        print(f"Change: R{change:.2f}")
                        # COLLECTION DETAILS
                        print("\n===== COLLECTION DETAILS =====")
                        print("Please collect your rented book at the VUT Library.")
                        print("Collection Desk: Main Circulation Desk")
                        print("Library Hours: 08:00 - 16:00")
                        print("Bring your Student Card and Payment Receipt for verification.")

                else:
                    print("Insufficient payment.")

    else:
        print("Book not found.")


# =====================================
# BOOK CONFERENCE ROOM
# =====================================

def book_conference_room(student_number):

    print("\n===== CONFERENCE ROOMS =====")

    for room, details in conference_rooms.items():

        if details["available"]:
            status = "Available"
        else:
            status = "Booked"

        print(f"\n{room}")
        print(f"Capacity: {details['capacity']}")
        print(f"Status: {status}")

    room_name = input("\nEnter room name to book: ")

    if room_name in conference_rooms:

        if conference_rooms[room_name]["available"]:

            choice = input(
                "Book room for 1 hour? (yes/no): "
            )

            if choice.lower() == "yes":

                start_time = datetime.now()
                end_time = start_time + timedelta(hours=1)

                conference_rooms[room_name]["available"] = False

                print("\n===== BOOKING CONFIRMED =====")
                print("Student Number:", student_number)
                print("Room:", room_name)
                print(
                    "Start Time:",
                    start_time.strftime("%H:%M")
                )
                print(
                    "End Time:",
                    end_time.strftime("%H:%M")
                )

            else:
                print("Booking cancelled.")

        else:
            print(
                "\nSorry, this room is already booked."
            )

    else:
        print("Room not found.")


# =====================================
# VIEW ALL BOOKS
# =====================================

def view_books():

    print("\n===== AVAILABLE LIBRARY BOOKS =====")

    for book, details in books.items():

        print("\nTitle:", book)
        print("Author:", details["author"])
        print("Released:", details["date_released"])

        if details["type"] == "free":
            print("Book Type: Free Borrowing")

        else:
            print(
                f"Book Type: Rental (R{details['price']})"
            )


# =====================================
# MAIN PROGRAM
# =====================================

student_number = login()

if student_number:

    while True:

        print("\n===== UNIVERSITY LIBRARY SYSTEM =====")
        print("1. Search Book")
        print("2. View All Books")
        print("3. Book Conference Room")
        print("4. Exit")

        option = input("Select Option: ")

        if option == "1":
            search_book()

        elif option == "2":
            view_books()

        elif option == "3":
            book_conference_room(student_number)

        elif option == "4":
            print("\nThank you for using the system.")
            break

        else:
            print("Invalid option.")
