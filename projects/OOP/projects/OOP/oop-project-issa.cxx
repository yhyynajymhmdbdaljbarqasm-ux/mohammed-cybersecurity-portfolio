#include <iostream> // مكتبة الإدخال والإخراج (cout, cin)
#include <string>   // مكتبة النصوص (Strings)
#include <cmath>    // مكتبة الدوال الرياضية مثل abs, sqrt
using namespace std; // لتجنب كتابة std:: قبل كل شيء

// ================== Switch Class ==================
class Switch {
private:
    string name;       // اسم السويتش
    int ports;         // عدد المنافذ
    int securityLevel; // مستوى الأمان
public:
    // Constructor مع قيم افتراضية
    Switch(string n = "SwitchX", int p = 0, int s = 0) : name(n), ports(p), securityLevel(s) {}
    void operator++() { securityLevel++; } // زيادة مستوى الأمان بمقدار 1 عند استخدام ++
    void operator+(const Switch& s) { ports += s.ports; } // جمع المنافذ بين سويتشين
    void printDetails() { cout << name << ": المنافذ=" << ports << ", الأمان=" << securityLevel << endl; } // طباعة تفاصيل السويتش
};

// ================== VPNConnection Class ==================
class VPNConnection {
private:
    string username;  // اسم المستخدم
    string IP;        // عنوان الـ IP
    bool connected;   // حالة الاتصال (متصل/غير متصل)
public:
    VPNConnection(string u = "userX", string ip = "0.0.0.0", bool c = false) : username(u), IP(ip), connected(c) {}
    void operator!() { connected = !connected; } // قلب حالة الاتصال
    bool operator==(const VPNConnection& v) { return username == v.username; } // مقارنة اسم المستخدم
    void printConnectionStatus() { 
        cout << "المستخدم: " << username 
             << "\nIP: " << IP 
             << "\nمتصل: " << (connected ? "نعم" : "لا") << endl; 
    } // طباعة حالة الاتصال
};

// ================== Server Class ==================
class Server {
private:
    string IP;         // عنوان IP السيرفر
    string serverName; // اسم السيرفر
    int load;          // التحميل على السيرفر
public:
    Server(string ip = "0.0.0.0", string name = "ServerX", int l = 0) : IP(ip), serverName(name), load(l) {}
    void operator+=(int additionalLoad) { load += additionalLoad; } // زيادة التحميل
    bool operator<=(const Server& s) { return load >= s.load; } // مقارنة التحميل بين سيرفرين
    void printDetails() { cout << "اسم السيرفر: " << serverName << "\nIP: " << IP << "\nالتحميل: " << load << endl; }
};

// ================== Router Class ==================
class Router {
private:
    string IP;      // عنوان IP الروتر
    string hostname; // اسم الروتر
    int ports;      // عدد المنافذ
public:
    Router(string ip = "0.0.0.0", string host = "RouterX", int p = 0) : IP(ip), hostname(host), ports(p) {}
    bool operator!=(const Router& r) { return IP != r.IP; } // مقارنة IP
    bool operator<(const Router& r) { return ports < r.ports; } // مقارنة عدد المنافذ
    void printDetails() { cout << "اسم الروتر: " << hostname << "\nIP: " << IP << "\nالبورتات: " << ports << endl; }
};

// ================== FirewallRule Class ==================
class FirewallRule {
private:
    int ruleID;       // رقم القاعدة
    string protocol;  // البروتوكول (TCP/UDP)
public:
    FirewallRule(int id = 0, string proto = "TCP") : ruleID(id), protocol(proto) {}
    bool operator==(const FirewallRule& r) { return protocol == r.protocol; } // مقارنة البروتوكول
    bool operator<(const FirewallRule& r) { return ruleID < r.ruleID; } // مقارنة القاعدة
    void printDetails() { cout << "قاعدة " << ruleID << ": " << protocol << endl; }
};

// ================== LoginSystem Class ==================
class LoginSystem {
private:
    static int attempts; // عدد محاولات تسجيل الدخول (مشترك بين كل الكائنات)
public:
    void loginAttempt() { attempts++; cout << "عدد محاولات تسجيل الدخول: " << attempts << endl; }
};
int LoginSystem::attempts = 0; // تهيئة المتغير الثابت

// ================== Car Class ==================
class Car {
private:
    int year;        // سنة الصنع
    string model;    // الموديل
    string brand;    // الماركة
public:
    Car() : year(0), model("Unknown"), brand("Unknown") {} // Constructor افتراضي
    Car(int y, string m, string b) : year(y), model(m), brand(b) {} // Constructor بقيم محددة
    void display() const { cout << "Brand: " << brand << "\nModel: " << model << "\nYear: " << year << endl; }
};

// ================== Counter Class ==================
class Counter {
private:
    int value; // قيمة العداد
public:
    Counter(int v = 0) : value(v) {}
    int getValue() const { return value; }
    void setValue(int v) { value = v; }
    void increment() { value++; } // زيادة العداد بمقدار 1
};
void increaseCounter(Counter& c) { c.setValue(c.getValue() + 1); } // دالة خارجية لزيادة العداد

// ================== Vector2D Class ==================
class Vector2D {
private:
    double x, y; // الإحداثيات
public:
    Vector2D(double xVal = 0, double yVal = 0) : x(xVal), y(yVal) {}
    Vector2D add(const Vector2D& v) const { return Vector2D(x + v.x, y + v.y); } // جمع متجهين
    void print() const { cout << "(" << x << ", " << y << ")" << endl; }
};

// ================== Fraction Class ==================
class Fraction {
private:
    int numerator, denominator; // البسط والمقام
    void simplify() { // تبسيط الكسر
        int gcd = findGCD(abs(numerator), abs(denominator));
        numerator /= gcd;
        denominator /= gcd;
        if (denominator < 0) { numerator = -numerator; denominator = -denominator; }
    }
    int findGCD(int a, int b) { while (b != 0) { int t = b; b = a % b; a = t; } return a; } // إيجاد القاسم المشترك الأكبر
public:
    Fraction(int num = 0, int denom = 1) : numerator(num), denominator(denom) { if(denom==0) denominator=1; simplify(); }
    Fraction multiply(const Fraction& other) const { return Fraction(numerator * other.numerator, denominator * other.denominator); } // ضرب كسور
    void print() const { cout << numerator << "/" << denominator << endl; }
};

// ================== ComplexNumber Class ==================
class ComplexNumber {
private:
    double real, imag; // الجزء الحقيقي والتخيلي
public:
    ComplexNumber(double r=0, double i=0): real(r), imag(i) {}
    ComplexNumber add(const ComplexNumber& other) const { return ComplexNumber(real + other.real, imag + other.imag); } // جمع أعداد مركبة
    void print() const { cout << real << (imag>=0? " + ":" - ") << abs(imag) << "i" << endl; }
};

// ================== Box Class ==================
class Box {
private:
    double length, width, height; // أبعاد الصندوق
public:
    Box(double l=0,double w=0,double h=0): length(l), width(w), height(h) {}
    double volume() const { return length * width * height; } // حساب الحجم
};
double sumVolumes(const Box& b1,const Box& b2) { return b1.volume()+b2.volume(); } // مجموع حجم صندوقين

// ================== Time Class ==================
class Time {
private:
    int hour, minute, second; // الساعة والدقيقة والثانية
public:
    Time(int h=0,int m=0,int s=0): hour(h),minute(m),second(s) {}
    int getHour() const {return hour;}
    int getMinute() const {return minute;}
    int getSecond() const {return second;}
};
bool isTime1Greater(const Time& t1,const Time& t2){ // مقارنة وقتين
    if(t1.getHour()>t2.getHour()) return true;
    if(t1.getHour()<t2.getHour()) return false;
    if(t1.getMinute()>t2.getMinute()) return true;
    if(t1.getMinute()<t2.getMinute()) return false;
    return t1.getSecond()>t2.getSecond();
}

// ================== Circle Class ==================
class Circle {
private:
    double radius; // نصف القطر
public:
    Circle(double r=0): radius(r) {}
    double getRadius() const { return radius; }
};
void printArea(const Circle& c){ cout << "مساحة الدائرة: " << 3.14159265358979323846*c.getRadius()*c.getRadius() << endl; }

// ================== Player Class ==================
class Player {
private:
    static int activePlayers; // عدد اللاعبين النشطين
public:
    Player(){ activePlayers++; }
    ~Player(){ activePlayers--; }
    static void showActivePlayers(){ cout << "عدد اللاعبين النشطين: " << activePlayers << endl; }
};
int Player::activePlayers=0;

// ================== Employee Class ==================
class Employee {
private:
    string name, position; // الاسم والوظيفة
    double salary;         // الراتب
public:
    void setName(string n){name=n;}
    void setPosition(string p){position=p;}
    void setSalary(double s){salary=s>=0?s:0;}
    string getName(){return name;}
    string getPosition(){return position;}
    double getSalary(){return salary;}
    void printInfo(){ cout << "الاسم: " << name << "\nالوظيفة: " << position << "\nالراتب: " << salary << endl; }
};

// ================== ChatApp Class ==================
class ChatApp {
public:
    void showWelcome(){
        static bool firstTime=true; // للتأكد من ظهور رسالة الترحيب مرة واحدة
        if(firstTime){ cout<<"مرحباً بك في تطبيق الدردشة!"<<endl; firstTime=false; }
        else { cout<<"أهلاً مجدداً!"<<endl; }
    }
};

// ================== BankAccount Class ==================
class BankAccount {
private:
    int accountNumber; // رقم الحساب
    double balance;    // الرصيد
public:
    BankAccount(int accNum=0,double initialBalance=0){ accountNumber=accNum; balance=initialBalance>=0?initialBalance:0; }
    void deposit(double amount){ if(amount>0) balance+=amount; else cout<<"خطأ\n"; }
    void withdraw(double amount){ if(amount>balance) cout<<"رصيد غير كافي\n"; else if(amount<0) cout<<"خطأ\n"; else balance-=amount;}
    double getBalance(){ return balance; }
    void printAccountInfo(){ cout<<"رقم الحساب: "<<accountNumber<<"\nالرصيد: "<<balance<<endl; }
};

// ================== Book Class ==================
class Book {
private:
    string title, author; // العنوان والمؤلف
    int year;             // سنة النشر
public:
    Book(){ title="غير معروف"; author="غير معروف"; year=0; }
    Book(string t){ title=t; author="غير معروف"; year=0; }
    Book(string t,string a,int y){ title=t; author=a; year=y; }
    void printInfo(){ cout<<"العنوان: "<<title<<"\nالمؤلف: "<<author<<"\nسنة النشر: "<<year<<endl; }
};

// ================== Sales Class ==================
class Sales {
private:
    static double totalSales; // إجمالي المبيعات
public:
    void addSale(double amount){ totalSales+=amount; }
    void showTotal() const { cout<<"مجموع المبيعات: "<<totalSales<<endl; }
};
double Sales::totalSales=0.0;

// ================== Invoice Class ==================
class Invoice {
private:
    static int nextInvoiceNumber; // الرقم التالي للفاتورة
    int invoiceNumber;            // رقم الفاتورة
public:
    Invoice(){ invoiceNumber=nextInvoiceNumber++; }
    void printInvoice() const { cout<<"رقم الفاتورة: "<<invoiceNumber<<endl; }
};
int Invoice::nextInvoiceNumber=1000;

// ================== Main Menu ==================
int main() {
    int choice;
    while(1){
        cout << "\n=== قائمة الواجبات ===\n";
        cout << "1- Switch\n2- VPNConnection\n3- Server\n4- Router\n5- FirewallRule\n6- LoginSystem\n7- Car\n8- Counter\n9- Vector2D\n10- Fraction\n";
        cout << "11- ComplexNumber\n12- Box\n13- Time\n14- Circle\n15- Player\n16- Employee\n17- ChatApp\n18- BankAccount\n19- Book\n20- Sales\n21- Invoice\n0- خروج\n";
        cout << "اختر رقم الواجب: ";
        cin >> choice;
        cin.ignore();

        switch(choice){
            case 1: { Switch s1("S1",10,5), s2("S2",20,8); ++s1; s1+s2; s1.printDetails(); s2.printDetails(); break; }
            case 2: { VPNConnection v1("u1","192.168.1.1",true), v2("u2","192.168.1.2",false); !v1; cout<<(v1==v2?"نفس المستخدم":"مستخدمين مختلفين")<<endl; v1.printConnectionStatus(); v2.printConnectionStatus(); break; }
            case 3: { Server s1("192.168.1.1","S1",50), s2("192.168.1.2","S2",70); s1+=20; cout<<(s1<=s2?"S1>=S2":"S1<S2")<<endl; s1.printDetails(); s2.printDetails(); break; }
            case 4: { Router r1("192.168.1.1","R1",4), r2("192.168.1.2","R2",8); cout<<(r1!=r2?"IP مختلف":"IP نفس")<<endl; cout<<(r1<r2?"ر1 أقل بورتات":"ر1 أكبر أو متساوي بورتات")<<endl; r1.printDetails(); r2.printDetails(); break; }
            case 5: { FirewallRule f1(1,"TCP"), f2(2,"UDP"); cout<<(f1==f2?"البروتوكول نفس":"البروتوكول مختلف")<<endl; cout<<(f1<f2?"قاعدة1 أولى":"قاعدة2 أولى")<<endl; f1.printDetails(); f2.printDetails(); break; }
            case 6: { LoginSystem l1,l2; l1.loginAttempt(); l2.loginAttempt(); l1.loginAttempt(); break; }
            case 7: { Car c1; Car c2(2022,"Camry","Toyota"); c1.display(); c2.display(); break; }
            case 8: { Counter c(5); cout<<"قبل: "<<c.getValue()<<endl; increaseCounter(c); cout<<"بعد: "<<c.getValue()<<endl; break; }
            case 9: { Vector2D v1(2,3), v2(4.5,1.5); Vector2D res=v1.add(v2); v1.print(); v2.print(); res.print(); break; }
            case 10:{ Fraction f1(2,3), f2(4,5); Fraction res=f1.multiply(f2); f1.print(); f2.print(); res.print(); break; }
            case 11:{ ComplexNumber c1(3.5,2), c2(1.5,-4); ComplexNumber sum=c1.add(c2); c1.print(); c2.print(); sum.print(); break; }
            case 12:{ Box b1(2,3,4), b2(1.5,2.5,3.5); cout<<"مجموع الحجم: "<<sumVolumes(b1,b2)<<endl; break; }
            case 13:{ Time t1(10,30,45), t2(10,30,30); cout<<(isTime1Greater(t1,t2)?"الوقت الأول أكبر":"الوقت الثاني أكبر أو متساوي")<<endl; break; }
            case 14:{ Circle c(5); printArea(c); break; }
            case 15:{ Player::showActivePlayers(); Player p1; Player::showActivePlayers(); { Player p2,p3; Player::showActivePlayers(); } Player::showActivePlayers(); break; }
            case 16:{ Employee e; e.setName("محمد"); e.setPosition("مبرمج"); e.setSalary(4500); e.printInfo(); break; }
            case 17:{ ChatApp app; app.showWelcome(); app.showWelcome(); app.showWelcome(); break; }
            case 18:{ BankAccount acc(1001,500); acc.printAccountInfo(); acc.deposit(200); acc.printAccountInfo(); acc.withdraw(100); acc.printAccountInfo(); acc.withdraw(1000); break; }
            case 19:{ Book b1,b2("مقدمة في C++"),b3("مبادئ امن سبراني","محمد علي",2023); b1.printInfo(); b2.printInfo(); b3.printInfo(); break; }
            case 20:{ Sales s1,s2; s1.addSale(150.5); s2.addSale(200.75); s1.addSale(99.25); s1.showTotal(); s2.showTotal(); break; }
            case 21:{ Invoice i1,i2,i3; i1.printInvoice(); i2.printInvoice(); i3.printInvoice(); break; }
            case 0: exit(0);
            default: cout<<"اختيار غير صحيح\n"; break;
        }
    }
    return 0;
}