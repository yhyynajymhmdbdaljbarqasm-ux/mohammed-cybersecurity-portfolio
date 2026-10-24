#include <iostream> // مكتبة الإدخال والإخراج
#include <string>   // مكتبة النصوص
using namespace std;

// ================== كلاس تجريدي Person ==================
class Person {
protected:
    string name; // اسم الشخص
    int age;     // عمر الشخص
    string ID;   // معرف الشخص
public:
    Person() : name("غير معروف"), age(0), ID("N/A") {} // كونستركتور افتراضي
    Person(string n, int a, string id) : name(n), age(a), ID(id) {} // كونستركتور مع بيانات
    virtual void printInfo() const = 0; // دالة افتراضية تجريدية
    string getID() const { return ID; }
    string getName() const { return name; }
    void setName(string n) { name = n; }
    void setAge(int a) { age = a; }
    virtual ~Person() {} // ديستركتور افتراضي
};

// ================== كلاس Doctor ==================
class Doctor : public Person {
private:
    string department; // القسم الطبي
    string specialty;  // التخصص
public:
    static int doctorCount; // عداد للأطباء
    Doctor() : Person(), department("غير محدد"), specialty("غير محدد") { doctorCount++; }
    Doctor(string n,int a,string id,string dept,string spec)
        : Person(n,a,id), department(dept), specialty(spec) { doctorCount++; }
    void setDepartment(string dept) { department = dept; }
    void setSpecialty(string spec) { specialty = spec; }
    string getDepartment() const { return department; }
    string getSpecialty() const { return specialty; }
    void printInfo() const override {
        cout << "الطبيب: " << name << " | التخصص: " << specialty
             << " | القسم: " << department << " | العمر: " << age << " | ID: " << ID << endl;
    }
};
int Doctor::doctorCount = 0;

// ================== كلاس Patient ==================
class Patient : public Person {
private:
    string department;       // القسم الطبي
    string roomNumber;       // رقم الغرفة
    double severity;         // درجة خطورة الحالة
    Doctor attendingDoctor;  // الطبيب المعالج
public:
    Patient() : Person(), department("N/A"), roomNumber("N/A"), severity(0.0) {}
    Patient(string n,int a,string id,string dept,string room,double sev,Doctor doc)
        : Person(n,a,id), department(dept), roomNumber(room), severity(sev), attendingDoctor(doc) {}
    void setDepartment(string dept) { department = dept; }
    void setRoomNumber(string room) { roomNumber = room; }
    void setSeverity(double sev) { severity = sev; }
    void setAttendingDoctor(Doctor doc) { attendingDoctor = doc; }
    string getDepartment() const { return department; }
    double getSeverity() const { return severity; }
    Doctor getAttendingDoctor() const { return attendingDoctor; }
    void printInfo() const override {
        cout << "المريض: " << name << " | القسم: " << department
             << " | الغرفة: " << roomNumber
             << " | درجة الخطورة: " << severity
             << " | الطبيب المعالج: " << attendingDoctor.getName()
             << " (" << attendingDoctor.getSpecialty() << ")\n";
    }
};

// ================== كلاس HospitalSystem ==================
class HospitalSystem {
private:
    static const int MAX_DOCTORS = 100;
    static const int MAX_PATIENTS = 200;
    Doctor doctors[MAX_DOCTORS];
    Patient patients[MAX_PATIENTS];
    int doctorCount = 0;
    int patientCount = 0;

    // ================== دوال البحث ==================
    Doctor* findDoctorByID(string id){
        for(int i=0;i<doctorCount;i++)
            if(doctors[i].getID()==id) return &doctors[i];
        return nullptr;
    }
    Patient* findPatientByID(string id){
        for(int i=0;i<patientCount;i++)
            if(patients[i].getID()==id) return &patients[i];
        return nullptr;
    }

    // ================== دوال إدخال آمنة ==================
    int inputInt(string prompt){
        int val;
        while(true){
            cout << prompt;
            cin >> val;
            if(cin.fail()){ cin.clear(); cin.ignore(1000,'\n'); cout << "❌ إدخال غير صحيح\n"; }
            else return val;
        }
    }
    double inputDouble(string prompt){
        double val;
        while(true){
            cout << prompt;
            cin >> val;
            if(cin.fail()){ cin.clear(); cin.ignore(1000,'\n'); cout << "❌ إدخال غير صحيح\n"; }
            else return val;
        }
    }

public:
    // ================== دوال إدارة الأطباء ==================
    void addDoctor(){
        if(doctorCount>=MAX_DOCTORS){ cout << "لا يمكن إضافة المزيد من الأطباء\n"; return; }
        string name, dept, spec, id;
        int age;
        cin.ignore();
        cout << "ادخل اسم الطبيب: "; getline(cin,name);
        cout << "ادخل القسم الطبي: "; getline(cin,dept);
        cout << "ادخل التخصص: "; getline(cin,spec);
        age = inputInt("ادخل العمر: ");
        cout << "ادخل ID الطبيب: "; cin >> id;
        if(findDoctorByID(id)) { cout << "هذا ID موجود مسبقا\n"; return; }
        doctors[doctorCount++] = Doctor(name,age,id,dept,spec);
        cout << "تمت إضافة الطبيب ✅\n";
    }

    void editDoctor(){
        string id; cout << "ادخل ID الطبيب لتعديله: "; cin >> id;
        Doctor* d = findDoctorByID(id);
        if(!d){ cout << "الطبيب غير موجود\n"; return; }
        cin.ignore();
        string name, dept, spec;
        int age;
        cout << "ادخل الاسم الجديد: "; getline(cin,name);
        cout << "ادخل القسم الجديد: "; getline(cin,dept);
        cout << "ادخل التخصص الجديد: "; getline(cin,spec);
        age = inputInt("ادخل العمر الجديد: ");
        d->setName(name); d->setDepartment(dept); d->setSpecialty(spec); d->setAge(age);
        cout << "تم تعديل بيانات الطبيب ✅\n";
    }

    void deleteDoctor(){
        string id; cout << "ادخل ID الطبيب لحذفه: "; cin >> id;
        for(int i=0;i<doctorCount;i++){
            if(doctors[i].getID()==id){
                for(int j=i;j<doctorCount-1;j++) doctors[j]=doctors[j+1];
                doctorCount--;
                cout << "تم حذف الطبيب ✅\n";
                return;
            }
        }
        cout << "الطبيب غير موجود ❌\n";
    }

    void printAllDoctors(){
        if(doctorCount==0){ cout << "لا يوجد أطباء\n"; return; }
        for(int i=0;i<doctorCount;i++) doctors[i].printInfo();
    }

    // ================== دوال إدارة المرضى ==================
    void addPatient(){
        if(patientCount>=MAX_PATIENTS){ cout << "لا يمكن إضافة المزيد من المرضى\n"; return; }
        if(doctorCount==0){ cout << "لا يوجد أطباء لتعيين مشرف\n"; return; }
        string name, dept, room, id, docID;
        int age;
        double severity;
        cin.ignore();
        cout << "ادخل اسم المريض: "; getline(cin,name);
        age = inputInt("ادخل العمر: ");
        cout << "ادخل ID المريض: "; cin >> id;
        if(findPatientByID(id)) { cout << "هذا ID موجود مسبقا\n"; return; }
        cin.ignore();
        cout << "ادخل القسم الطبي: "; getline(cin,dept);
        cout << "ادخل رقم الغرفة: "; getline(cin,room);
        severity = inputDouble("ادخل درجة خطورة الحالة: ");
        cout << "قائمة الأطباء:\n";
        for(int i=0;i<doctorCount;i++) cout << doctors[i].getID() << " - " << doctors[i].getName() << endl;
        cout << "ادخل ID الطبيب المعالج: "; cin >> docID;
        Doctor* doc = findDoctorByID(docID);
        if(!doc){ cout << "الطبيب غير موجود\n"; return; }
        patients[patientCount++] = Patient(name,age,id,dept,room,severity,*doc);
        cout << "تمت إضافة المريض ✅\n";
    }

    void editPatient(){
        string id; cout << "ادخل ID المريض لتعديله: "; cin >> id;
        Patient* p = findPatientByID(id); // مؤشر للمريض
        if(!p){ cout << "المريض غير موجود\n"; return; }
        cin.ignore();
        string name, dept, room, docID;
        int age;
        double severity;
        cout << "ادخل الاسم الجديد: "; getline(cin,name);
        age = inputInt("ادخل العمر الجديد: ");
        cout << "ادخل القسم الجديد: "; getline(cin,dept);
        cout << "ادخل رقم الغرفة الجديد: "; getline(cin,room);
        severity = inputDouble("ادخل درجة خطورة الحالة الجديدة: ");
        cout << "قائمة الأطباء:\n";
        for(int i=0;i<doctorCount;i++) cout << doctors[i].getID() << " - " << doctors[i].getName() << endl;
        cout << "ادخل ID الطبيب المعالج الجديد: "; cin >> docID;
        Doctor* doc = findDoctorByID(docID);
        if(!doc){ cout << "الطبيب غير موجود\n"; return; }
        p->setName(name); p->setAge(age); p->setDepartment(dept);
        p->setRoomNumber(room); p->setSeverity(severity); p->setAttendingDoctor(*doc);
        cout << "تم تعديل بيانات المريض ✅\n";
    }

    void deletePatient(){
        string id; cout << "ادخل ID المريض لحذفه: "; cin >> id;
        for(int i=0;i<patientCount;i++){
            if(patients[i].getID()==id){
                for(int j=i;j<patientCount-1;j++) patients[j]=patients[j+1];
                patientCount--;
                cout << "تم حذف المريض ✅\n";
                return;
            }
        }
        cout << "المريض غير موجود ❌\n";
    }

    void printAllPatients(){
        if(patientCount==0){ cout << "لا يوجد مرضى\n"; return; }
        for(int i=0;i<patientCount;i++) patients[i].printInfo();
    }

    void printStats(){
        if(patientCount==0){ cout << "لا يوجد مرضى\n"; return; }
        double sum=0, maxS=patients[0].getSeverity(), minS=patients[0].getSeverity();
        for(int i=0;i<patientCount;i++){
            double s = patients[i].getSeverity();
            sum+=s;
            if(s>maxS) maxS=s;
            if(s<minS) minS=s;
        }
        cout << "عدد المرضى: "<<patientCount
             << "\nأعلى درجة خطورة: "<<maxS
             << "\nأقل درجة خطورة: "<<minS
             << "\nمتوسط درجة الخطورة: "<<(sum/patientCount)<<endl;
    }
};

// ================== الدالة الرئيسية ==================
int main() {
    HospitalSystem hospital;
    int choice = 0;
    while(choice != 10){
        cout << "\n--- نظام إدارة المستشفى ---\n";
        cout << "1- إضافة طبيب\n2- تعديل طبيب\n3- حذف طبيب\n4- عرض الأطباء\n";
        cout << "5- إضافة مريض\n6- تعديل مريض\n7- حذف مريض\n8- عرض المرضى\n";
        cout << "9- احصائيات\n10- خروج\n";
        cout << "اختر: ";
        cin >> choice;
        switch(choice){
            case 1: hospital.addDoctor(); break;
            case 2: hospital.editDoctor(); break;
            case 3: hospital.deleteDoctor(); break;
            case 4: hospital.printAllDoctors(); break;
            case 5: hospital.addPatient(); break;
            case 6: hospital.editPatient(); break;
            case 7: hospital.deletePatient(); break;
            case 8: hospital.printAllPatients(); break;
            case 9: hospital.printStats(); break;
            case 10: cout << "انهاء البرنامج ✅\n"; break;
            default: cout << "خيار غير صحيح ❌\n";
        }
    }
    return 0;
}