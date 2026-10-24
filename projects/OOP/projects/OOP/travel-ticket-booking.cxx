#include <iostream>
#include <vector>
#include <string>
using namespace std;

// ==========================
// كلاس Passenger (المسافر)
// ==========================
class Passenger {
private:
    string name;      // اسم المسافر
    string passport;  // رقم الجواز
    int age;          // عمر المسافر

public:
    // Constructor (دالة البناء)
    Passenger(string n, string p, int a) : name(n), passport(p), age(a) {}

    // Getter Methods (للوصول للبيانات)
    string getName() { return name; }
    string getPassport() { return passport; }
    int getAge() { return age; }

    // عرض بيانات المسافر
    void displayInfo() {
        cout << "👤 مسافر: " << name
             << " | جواز: " << passport
             << " | عمر: " << age << endl;
    }
};

// ==========================
// كلاس Flight (الرحلة) - أساسي
// ==========================
class Flight {
protected:
    string flightNumber;  // رقم الرحلة
    string destination;   // الوجهة
    string date;          // التاريخ
    double price;         // السعر

public:
    // Constructor
    Flight(string fn, string dest, string d, double p)
        : flightNumber(fn), destination(dest), date(d), price(p) {}

    // دالة عرض المعلومات (قابلة لإعادة التعريف)
    virtual void displayInfo() {
        cout << "✈️ رحلة: " << flightNumber
             << " | وجهة: " << destination
             << " | تاريخ: " << date
             << " | سعر: $" << price << endl;
    }

    // Getters
    double getPrice() { return price; }
    string getFlightNumber() { return flightNumber; }
};

// ==========================
// كلاس DomesticFlight (رحلة داخلية)
// يرث من Flight
// ==========================
class DomesticFlight : public Flight {
public:
    DomesticFlight(string fn, string dest, string d, double p)
        : Flight(fn, dest, d, p) {}

    // إعادة تعريف دالة العرض (Polymorphism)
    void displayInfo() override {
        cout << "🏠 [داخلية] ";
        Flight::displayInfo();
    }
};

// ==========================
// كلاس InternationalFlight (رحلة دولية)
// يرث من Flight
// ==========================
class InternationalFlight : public Flight {
public:
    InternationalFlight(string fn, string dest, string d, double p)
        : Flight(fn, dest, d, p) {}

    // إعادة تعريف دالة العرض (Polymorphism)
    void displayInfo() override {
        cout << "🌍 [دولية] ";
        Flight::displayInfo();
    }
};

// ==========================
// كلاس Ticket (تذكرة) - يستخدم Composition
// ==========================
class Ticket {
private:
    int ticketID;       // رقم التذكرة
    Passenger passenger; // المسافر
    Flight* flight;      // مؤشر للرحلة (قد تكون داخلية أو دولية)

public:
    // Constructor
    Ticket(int id, Passenger p, Flight* f)
        : ticketID(id), passenger(p), flight(f) {}

    // عرض بيانات التذكرة
    void displayInfo() {
        cout << "🎫 رقم التذكرة: " << ticketID << endl;
        passenger.displayInfo();
        flight->displayInfo();
    }
};

// ==========================
// كلاس BookingSystem (نظام الحجز)
// ==========================
class BookingSystem {
private:
    vector<Flight*> flights; // قائمة الرحلات
    vector<Ticket> tickets;  // قائمة التذاكر
    int ticketCounter = 1;   // عداد للتذاكر

public:
    // إضافة رحلة للنظام
    void addFlight(Flight* f) {
        flights.push_back(f);
    }

    // عرض جميع الرحلات
    void showFlights() {
        cout << "\n📋 الرحلات المتاحة:\n";
        for (size_t i = 0; i < flights.size(); i++) {
            cout << i + 1 << ". ";
            flights[i]->displayInfo();
        }
    }

    // حجز تذكرة لمسافر
    void bookTicket(Passenger p, int flightIndex) {
        if (flightIndex < 1 || flightIndex > flights.size()) {
            cout << "❌ اختيار غير صحيح!\n";
            return;
        }
        // إنشاء تذكرة جديدة
        Ticket t(ticketCounter++, p, flights[flightIndex - 1]);
        tickets.push_back(t);

        cout << "✅ تم الحجز بنجاح!\n";
        t.displayInfo();
    }
};

// ==========================
// Main Function
// ==========================
int main() {
    BookingSystem system;

    // إضافة رحلات للنظام
    system.addFlight(new DomesticFlight("YEM123", "صنعاء", "2025-09-10", 150));
    system.addFlight(new DomesticFlight("YEM456", "عدن", "2025-09-12", 120));
    system.addFlight(new InternationalFlight("INT789", "القاهرة", "2025-09-15", 400));

    // عرض الرحلات
    system.showFlights();

    // إنشاء مسافرين وحجز تذاكر لهم
    Passenger p1("محمد", "P12345", 17);
    system.bookTicket(p1, 2); // حجز رحلة رقم 2 (عدن)

    Passenger p2("أحمد", "P67890", 25);
    system.bookTicket(p2, 3); // حجز رحلة رقم 3 (القاهرة)

    return 0;
}